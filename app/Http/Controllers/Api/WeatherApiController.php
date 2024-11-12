<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;


class WeatherApiController extends Controller
{
    public function index(){
        // List of Cities
        $spreadsheet = IOFactory::load('../public/cities.xlsx');

        // Select the active sheet
        $sheet = $spreadsheet->getActiveSheet();
        $names = [];
        $rowIndex = 0;

        foreach ($sheet->getRowIterator() as $row) {
            if ($rowIndex === 0) {
                $rowIndex++;
                continue; // Skip the first row (header)
            }

            $cell = $row->getCellIterator();
            $cell->setIterateOnlyExistingCells(false);
            $cellArray = iterator_to_array($cell);

            if (!empty($cellArray) && isset($cellArray['A'])) {
                $names[] = $cellArray['A']->getValue(); // Get value of the first column
            }

            $rowIndex++;
        }

        // Remove the header if necessary
        // array_shift($names);
        return view('weather.index', compact('names'));
    }

    public function getWeather(Request $request)
    {
        $city = $request->input('city');
        $weatherApiKey  = '6a40ecc036e00f08f67b3ff49bd3effb'; // openweathermap API Key
        $cityUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$weatherApiKey}&units=metric";
        // &units=metric for temperature in Celsius

        try {
            // Create a Guzzle client instance
            $client = new Client();
            $cityResponse = $client->get($cityUrl, ['verify' => false]);
            $cityData = json_decode($cityResponse->getBody()->getContents(), true);
            return response()->json($cityData);
        } catch (Exception $e) {
            // Handle exceptions and errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
        /* NASA API
            $latitude = 34.05;
            $longitude = -118.25;
            $apiKey = 'GxWkbTbxGEIZ4hkiVyZfjSQDiK5sX15LOvZFxEYg'; // NASA API Key
            $url = "https://api.nasa.gov/insight_weather/";
            // Get latitude and longitude from city name
            $geocodeResponse = $client->get($geocodeUrl);
            $geocodeData = json_decode($geocodeResponse->getBody()->getContents(), true);
            if (empty($geocodeData)) {
                return response()->json(['error' => 'City not found'], 404);
            }

            // Extract latitude and longitude
            $latitude = $geocodeData[0]['lat'];
            $longitude = $geocodeData[0]['lon'];

            // Make the API request
            $response = $client->get($url, [
                'query' => [
                    'api_key'   => $apiKey, // Pass the API key here
                    'latitude'  => $latitude,
                    'longitude' => $longitude,
                    'feedtype'  => 'json',
                    'ver'       => '1.0'
                ],
                'verify' => false // Disable SSL certificate verification
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody()->getContents(), true);
            // dd($data);
            // Return the data as JSON response
            // return response()->json($data);


            // Extract temperature
            $sol_keys = $data['sol_keys'];
            $latest_sol = $sol_keys[0]; // Assuming you want the latest day's data
            $temperature = $data[$latest_sol]['AT']['av']; // Average temperature
            $pressure = $data[$latest_sol]['PRE']['av']; // Average pressure

            // Pass temperature data to the view
            return view('weather', compact('temperature','pressure'));
        */
