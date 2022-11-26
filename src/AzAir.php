<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;


class AzAir
{
    private $destinations = [];
    private $maxPrice;
    private $maxStayPeriod;
    private $minStayPeriod;
    private $from;
    private $to;
    private $onlyWeekends;
    private $exceptionDays;

    const CURRENCY = 'PLN';

    /**
     * AzAir constructor.
     *
     * @param array $countries
     * @param array $config
     *
     * @throws Exception
     */
    public function __construct(array $countries, array $config)
    {
        $onlyCapitals = $config['onlyCapitals'] ?? false;
        $this->maxPrice = $config['maxPrice'] ?? 300;
        $this->minStayPeriod = $config['minStayPeriod'] ?? 2;
        $this->maxStayPeriod = $config['maxStayPeriod'] ?? 5;
        $this->from = $config['from'] ?? (new Carbon())->format('d.m.Y');
        $this->to = $config['to'] ?? (new Carbon())->addDays(31)->format('d.m.Y');
        $this->onlyWeekends = $config['onlyWeekends'] ?? false;
        $this->exceptionDays = $config['exceptionDays'] ?? [];

        /** @var Country $country */
        foreach ($countries as $country) {
            /** @var Airport $airport */
            foreach ($country->getAirports() as $airport) {
                if (($onlyCapitals && $airport->isCapital()) || !$onlyCapitals) {
                    $this->destinations[] = $airport->getName();
                }
            }
        }
    }

    private function convertStringDateTimeToCarbon(string $stringDate, string $time)
    {
        $stringDate = substr($stringDate, 4);
        list($day, $month, $year) = explode('/', $stringDate);

        return new Carbon($day . '-' . $month . '-20' . $year . ' ' . $time);
    }

    private function formatDate(Carbon $date)
    {
        $weekDays = ['Nd', 'Pn', 'Wt', 'Sr', 'Czw', 'Pt', 'Sob'];

        return $weekDays[$date->dayOfWeek] . ' ' . $date->format('d.m H:i');
    }

    private function doRequest($destination, $waitTime = 3): string
    {
        $res = (new Client())->request(
            'GET',
            "https://www.azair.eu/azfin.php?tp=0&searchtype=flexi&srcAirport=Warszawa+%5BWAW%5D+%28%2BWMI%29&srcTypedText=warszaw&srcFreeTypedText=&srcMC=WAR_ALL&srcap0=WMI&srcFreeAirport=&dstAirport=$destination&dstTypedText=anyw&dstFreeTypedText=&dstMC=&adults=1&children=0&infants=0&minHourStay=0%3A45&maxHourStay=23%3A20&minHourOutbound=0%3A00&maxHourOutbound=24%3A00&minHourInbound=0%3A00&maxHourInbound=24%3A00&depdate=$this->from&arrdate=$this->to&minDaysStay=$this->minStayPeriod&maxDaysStay=$this->maxStayPeriod&nextday=0&autoprice=true&currency=" . self::CURRENCY . "&wizzxclub=false&flyoneclub=false&blueairbenefits=false&megavolotea=false&schengen=false&transfer=false&samedep=true&samearr=true&dep0=true&dep1=true&dep2=true&dep3=true&dep4=true&dep5=true&dep6=true&arr0=true&arr1=true&arr2=true&arr3=true&arr4=true&arr5=true&arr6=true&maxChng=1&isOneway=return&resultSubmit=Szukaj",
            []
        );

        $html = $res->getBody()->getContents();

        if (str_contains($html, "Nasza wyszukiwarka nie jest w stanie odpowiedzieć na zapytanie w odpowiednim czasie") || str_contains($html, "Our search engine is not able to answer your query in a timely manner")) {
            echo "Need to wait, because server is overloaded!\n";

            sleep($waitTime);

            return $this->doRequest($destination, $waitTime + 2);
        }

        return $html;
    }

    private function areTimeFiltersValid(Carbon $from, Carbon $to): bool
    {
        $period = CarbonPeriod::create($from->format('d.m.y'), $to->format('d.m.y'));
        $res = true;

        foreach ($this->exceptionDays as $day) {
            if ($period->contains($day)) {
                $res = false;
                break;
            }
        }

        if ($this->onlyWeekends) {
            return $res && $from->dayOfWeek >= 4 && $to->dayOfWeek <= 2;
        }

        return $res;
    }

    private function arePriceFiltersValid($price): bool
    {
        return $price < $this->maxPrice;
    }

    private function parseHtml($html)
    {
        $htmlDom = new Dom();
        $htmlDom->load($html);
        $citiesData = [];

        foreach ($htmlDom->find('.result') as $htmlDomNode) {
            $from = $this->convertStringDateTimeToCarbon($htmlDomNode->find('.text > p .date')[0]->text, $htmlDomNode->find('.text > p .from > strong')[0]->text);
            $to = $this->convertStringDateTimeToCarbon($htmlDomNode->find('.text > p .date')[1]->text, substr($htmlDomNode->find('.text > p .to')[1]->text, 0, 5));

            $airline = $htmlDomNode->find('.text .detail .airline')[0]->text;

            $price = intval(str_replace(' zł', '', $htmlDomNode->find('.text .totalPrice .tp')->text));

            if ($this->areTimeFiltersValid($from, $to) && $this->arePriceFiltersValid($price)) {
                $citiesData[] = [
                    'carbonFrom' => $from,
                    'carbonTo' => $to,
                    'from' => $this->formatDate($from),
                    'to' => $this->formatDate($to),
                    'fromLink' => $htmlDomNode->find('.text .detail a.bt.blue')->getAttribute('href'),
                    'airline' => $airline,
                    'price' => $price,
                ];
            }
        }

        return $citiesData;
    }

    private function generateResultHtmlFile($citiesData)
    {
        ob_start();
        ?>

        <style>
            td {
                padding: 8px;
            }
        </style>

        <table>
            <thead>
            <tr>
                <td>FROM</td>
                <td>TO</td>
                <td>DURATION</td>
                <td>DESTINATION</td>
                <td>LINIA</td>
                <td>PRICE</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($citiesData as $cityName => $cityData) : ?>
                <?php if (!empty($cityData)) : ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">==== <?php echo $cityName; ?> ====</td>
                    </tr>
                    <?php foreach ($cityData as $city) : ?>
                        <tr>
                            <td>
                                <?php echo $city['from'] ?>
                            </td>
                            <td>
                                <?php echo $city['to'] ?>
                            </td>
                            <td>
                                <?php echo number_format(Carbon::parse($city['carbonFrom'])->diffInMilliseconds($city['carbonTo']) / (1000 * 60 * 60 * 24), 2); ?> days
                            </td>
                            <td>
                                <?php echo $cityName; ?>
                            </td>
                            <td>
                                <?php echo $city['airline']; ?>
                            </td>
                            <td>
                                <?php echo $city['price']; ?>
                            </td>
                            <td>
                                <a href="https://www.azair.eu/<?php echo $city['fromLink']; ?>" target="_blank" referrerpolicy="no-referrer">BUY</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php file_put_contents(__DIR__ . '/index.html', ob_get_clean());
    }

    public function run()
    {
        $citiesData = [];

        foreach ($this->destinations as $destination) {
            echo "\n=== $destination ===\n";

            $html = $this->doRequest(urlencode($destination));
            file_put_contents('out/' . $destination . '.html', $html);
            $citiesData[$destination] = $this->parseHtml($html);

            echo count($citiesData[$destination]);

            sleep(1);
        }

        $this->generateResultHtmlFile($citiesData);
    }
}
