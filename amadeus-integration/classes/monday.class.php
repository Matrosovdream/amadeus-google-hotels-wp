<?php
class Monday_integration {

    private $this->base_url = 'https://api.monday.com/v2';

    private $api_key;
    private $board_id;

    public function __construct() {

        $this->api_key = get_option('monday_api_key');
        $this->board_id = get_option('monday_board_id');

    }

    public function query( $data ) {

        $key = $this->api_key;

        $curl = curl_init();

        $data['start_date'] = date('Y-m-d', strtotime($data['start_date']));
        $data['end_date'] = date('Y-m-d', strtotime($data['end_date']));
    
        // Not the best solution, rewrite!
        $fields = '{"query":"mutation {\\r\\n    create_item(\\r\\n        board_id: 6280680320, \\r\\n        group_id: \\"topics\\",\\r\\n        item_name: \\"'.$data['name'].'\\",\\r\\n        column_values: \\"{\\\\\\"date_1\\\\\\":\\\\\\"'.$data['start_date'].'\\\\\\", \\\\\\"date4\\\\\\":\\\\\\"'.$data['end_date'].'\\\\\\", \\\\\\"country_code\\\\\\":\\\\\\"'.$data['country_code'].'\\\\\\", \\\\\\"email_2\\\\\\":\\\\\\"'.$data['mail'].'\\\\\\", \\\\\\"phone\\\\\\":\\\\\\"'.$data['phone'].'\\\\\\", \\\\\\"text\\\\\\":\\\\\\"'.$data['message'].'\\\\\\", \\\\\\"text8\\\\\\":\\\\\\"'.$data['hotel_url'].'\\\\\\", \\\\\\"text1\\\\\\":\\\\\\"'.$data['hotel_name'].'\\\\\\"}\\"\\r\\n    ) \\r\\n    { id }\\r\\n}","variables":{}}';
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->base_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $fields,
          CURLOPT_HTTPHEADER => array(
            "Authorization: {$key}",
            "Content-type: application/json",
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

    }

}