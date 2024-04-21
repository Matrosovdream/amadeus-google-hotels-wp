<?php
class Amadeus_API {

    private $base_url = 'https://api.amadeus.com/';

    private $client_id;
    private $secret_id;

    public function __construct() {

        $this->client_id = get_option('amadeus_client_id');
        $this->secret_id = get_option('amadeus_secret_id');

    }

    public function get_hotels() {

        $places = new Google_places;

        $city = 'LON';
        $url = "{$this->base_url}/v1/reference-data/locations/hotels/by-city?cityCode=".$city;
        $data = $this->request( $url );

        $hotels = $data['data'];
        $hotels = array_slice( $hotels, 0, 3 );

        // Google info
        foreach( $hotels as $key=>$hotel ) {

            $geo = implode( ',', array( $hotel['geoCode']['latitude'], $hotel['geoCode']['longitude']) );
            $google_data = $places->get_hotel_info( $hotel['name'], $geo );

            $hotels[$key]['info'] = $google_data;

        }

        return $hotels;

    }

    public function get_extra( $args ) {

        $query = http_build_query( $args );
        $url = "{$this->base_url}/v3/shopping/hotel-offers?".$query; 

        $data = $this->request( $url );

    }

    private function get_token() {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "{$this->base_url}/v1/security/oauth2/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id={$this->client_id}&client_secret={$this->secret_id}");

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        return json_decode( $result, true );

    }

    private function request( $url ) {

        $auth = $this->get_token();
        $token = $auth['access_token'];

        $curls = curl_init();
        curl_setopt($curls, CURLOPT_URL, $url);
        curl_setopt($curls, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curls, CURLOPT_HTTPHEADER, array("Authorization: Bearer $token"));
        $res = curl_exec($curls);
        curl_close ($curls);

        if (curl_errno($curls)) {
            echo 'Error:' . curl_error($curls);
        }
        
        return json_decode( $res, true );

    }


}