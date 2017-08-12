<?php

namespace App\Http\Requests;

use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreTrip extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function __construct(ValidationFactory $validationFactory)
    {   
        $validationFactory->extend(
            'places_require',
            function ($attribute, $value, $parameters) { 
                $data = json_decode($value);
                if(empty($data)) {
                    return false;
                }
                return true;
            },
            'Your trip dont have any plan'
        );

        $validationFactory->extend(
            'places_check',
            function ($attribute, $value, $parameters) {  
                $data = json_decode($value);
                foreach ($data as $place) {
                    if ($place->index == 0) {
                        if (empty($place->name) || empty($place->lat) || empty($place->lng)) {
                            return false;
                        }
                    } else {
                        if (empty($place->name) || empty($place->lat) || empty($place->lng) || empty($place->activities) 
                            || empty($place->vehicle) || empty($place->time) || ($place->time < 0)) {
                            return false;
                        }
                    }
                }
                return true;
            },
            'Your trip information have some problems'
        );

        $validationFactory->extend(
            'close',
            function ($attribute, $value, $parameters) {
                $data = json_decode($value);
                if(!empty($data)) {
                    $start = $data[0];
                    $end = end($data); 
                    if (($start->lat != $end->lat) || ($start->lng != $end->lng) || ($start->name != $end->name)) {
                        return false;
                    }
                    return true;
                }
            },
            'You must go back to the start place'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => "required",
            "time_start" => "required|date|after:".Carbon::now(),
            "time_end" => "required|date",
            "cover_file" => "required|image",
            "places" => "places_require|places_check|close"
        ];
    }
}
