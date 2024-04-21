<?php
class Google_places {

    private $api_key;
    private $this->base_url = 'https://maps.googleapis.com/maps/api';

    public function __construct() {

        $this->api_key = get_option('googleplaces_api_key');

    }

    public function make_search( $query, $next_page_token='' ) {

        $name = $query.' | hotel';

        // First page
        $data = array(
            "query" => $name,
            "key" => $this->api_key,
            "radius" => 25,
            "types" => "lodging",
            "count" => 5
        );

        // We load just 20 results for now
        if( $next_page_token ) {
            $data['next_page_token'] = $next_page_token;
        }

        $url = "{$this->base_url}/place/textsearch/json?".http_build_query($data);
        $res = $this->request_get( $url );

        $places = $res['results'];

        // Details and images
        foreach( $places as $key=>$place ) {

            $place_info = $this->get_place_details( $place['place_id'] );

            if( is_countable( $place_info['photos'] ) > 0 ) {
                $place_info['photos'] = $this->get_place_photos( $place_info['photos'], 1 );
            } 

            $places[$key]['details'] = $place_info;

        }

        return $places;

    }

    public function get_hotel_info( $name, $location ) {

        // Step 1, place ID
        $place_id = $this->get_place_id( $name, $location );

        // Step 2, place details
        if( $place_id ) {
            $place_info = $this->get_place_details( $place_id );
        }

        // Step 3, place photos
        if( is_countable( $place_info['photos'] ) > 0 ) {
            $place_info['photos'] = $this->get_place_photos( $place_info['photos'] );
        } 

        return $place_info;

    }

    public function get_place_id( $name, $location ) {

        $data = array(
            "location" => $location,
            "query" => $name,
            "key" => $this->api_key,
            "radius" => 10
        );
        $url = "{$this->base_url}/place/textsearch/json?".http_build_query($data);
        $res = $this->request_get( $url );
        $place_id = $res['results'][0]['place_id'];

        return $place_id;

    }

    public function get_place_details( $place_id ) {

        $data = array(
            "place_id" => $place_id,
            "key" => $this->api_key,
        );
        $url = "{$this->base_url}//place/details/json?".http_build_query($data);
        $info = $this->request_get( $url );

        $info = $this->filter_place_details( $info['result'] );

        return $info;
    }

    private function get_place_photos( $photos_raw, $count=1 ) {

        $photos_raw = array_slice( $photos_raw, 0, $count );

        $photos = [];
        foreach( $photos_raw as $photo ) {
            $photos[] = $this->get_photo( $photo['photo_reference'] );
        }

        return $photos;

    }

    public function filter_place_details( $info ) {

        unset( $info['address_components'] );
        unset( $info['current_opening_hours'] );
        unset( $info['geometry'] );
        //unset( $info['opening_hours'] );
        //unset( $info['reviews'] );

        return $info;

    }

    private function request_get( $url ) {

        $result = wp_remote_get( $url );
        $res = json_decode( $result['body'], true );
        return $res;

    }

    private function get_photo($ref) {

        $data = array(
            "photo_reference" => $ref,
            "key" => $this->api_key,
            "maxwidth" => 1000
        );
        $url = "{$this->base_url}//place/photo?".http_build_query($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $href = $this->substr_url($response);

        return $href;
    }

    private function substr_url( $html ) {

        $pattern = '/<a\s[^>]*href="(.*?)"/i';
    
        if (preg_match($pattern, $html, $matches)) {
            // $matches[1] contains the href value
            return $matches[1];
        }
    
        // Return null if href attribute or URL is not found
        return null;

    }

}