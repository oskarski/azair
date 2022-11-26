<?php


use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

class Booking {
	const MAX_PAGES = 20;

	private function fetch() {
		do {
			$perPage = 25;
			$page = 0;
			$offset = $page * $perPage;
//			$res = ( new Client() )->request( 'GET', "https://www.booking.com/searchresults.pl.html?aid=304142&label=gen173nr-1FCAEoggI46AdIM1gEaLYBiAEBmAEJuAEHyAEN2AEB6AEB-AELiAIBqAIDuAKetcvuBcACAQ&sid=e65ef9bbb0df9daaaaf70682955de52c&tmpl=searchresults&checkin_month=1&checkin_monthday=26&checkin_year=2020&checkout_month=1&checkout_monthday=29&checkout_year=2020&class_interval=1&dest_id=-2595386&dest_type=city&from_sf=1&group_adults=1&group_children=0&label_click=undef&&nflt=distance%3D3000%3Bpri%3D1%3Bconcise_unit_type%3D0%3B&no_rooms=1&order=price&raw_dest_type=city&room1=A&sb_price_type=total&shw_aparth=1&slp_r_match=0&srpvid=9f1c7ff91e8601de&ss=Edinburgh&ssb=empty&ssne=Edinburgh&ssne_untouched=Edinburgh&top_ufis=1&rows=$perPage&offset=$offset&sr_ajax=1&b_gtt=dLYAeZFVJfNTBdVZXcbfdHPGVJfcbQLBZWHCWYC&_=1574100616992", [] );
//
//			$html = $res->getBody()->getContents();
//			file_put_contents('b.html', $html);
			$html = file_get_contents('b.html');

			$htmlDom = new Dom();
			$htmlDom->load( $html );

			$pagesText = $htmlDom->find('.bui-pagination__pages .bui-pagination__item .bui-u-inline');
			$pages = $pagesText[$pagesText->count() - 1]->text;

			foreach ($htmlDom->find('.sr_property_block') as $hotelNode) {
				$url = 'https://www.booking.com/' . $hotelNode->find('.hotel_name_link.url')->getAttribute('href');
				$url = 'https://www.booking.com/hotel/gb/mcgregors-guest-house.pl.html?aid=304142;label=gen173nr-1FCAEoggI46AdIM1gEaLYBiAEBmAEJuAEHyAEN2AEB6AEB-AELiAIBqAIDuAKetcvuBcACAQ;sid=e65ef9bbb0df9daaaaf70682955de52c;all_sr_blocks=82226912_100772297_0_0_0;checkin=2020-01-26;checkout=2020-01-29;dest_id=-2595386;dest_type=city;dist=0;group_adults=1;group_children=0;hapos=1;highlighted_blocks=82226912_100772297_0_0_0;hpos=1;nflt=distance%3D3000%3Bpri%3D1%3Bconcise_unit_type%3D0%3B;no_rooms=1;req_adults=1;req_children=0;room1=A;sb_price_type=total;sr_order=price;srepoch=1574103986;srpvid=f1b88659fb6c017c;type=total;ucfs=1&';
				$res = ( new Client() )->request( 'GET', $url, [] );
				$html = $res->getBody()->getContents();
				file_put_contents('b1.html', $html);
				$html = file_get_contents('b1.html');

				$htmlDom = new Dom();
				$htmlDom->load( $html );
				break;
			}
		} while (false && $page++ < $pages && $page < MAX_PAGES);
	}

	public function run() {
		$this->fetch();
	}
}