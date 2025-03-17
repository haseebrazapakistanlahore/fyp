<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\Models\Consumer;
use App\Models\Professional;

class FCMController extends Controller
{

    public function sendNotification(String $title, String $payloadBody, Array $additionalData, String $token)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($payloadBody)->setSound('default');


        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($additionalData);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        // $token = "foGkH_Q5pxE:APA91bE87ztcTlYL3-_XnhYRayDJkg9ri5_rVTPuIJmnAWp4qAv7g9pS_zCI0GYm3Y3vWzoEsWnrX6b1TjOm_Nz7gXJ4k0Hx4_aofIp85HQXKdVA8UC5ZlkOAQGe1KcazQcdceH7mS9I";

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $tokensToDelete = $downstreamResponse->tokensToDelete();
        if (count($tokensToDelete) > 0) {
            // dd("inside token to delete");
            Consumer::whereIn('token', $tokensToDelete)->update(['token' => NULL]);
            Professional::whereIn('token', $tokensToDelete)->update(['token' => NULL]);
        }

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $tokensToModify = $downstreamResponse->tokensToModify();
        if (count($tokensToModify) > 0) {
            // dd("inside token to modify");

            foreach ($tokensToModify as $oldToken => $newToken) {
                if(Consumer::where('token', $oldToken)->count()){
                    Consumer::where('token', $oldToken)->update(['token' => $newToken]);
                }

                if(Professional::where('token', $oldToken)->count()){
                    Professional::where('token', $oldToken)->update(['token' => $newToken]);
                }
            }
        }

        // return Array - you should try to resend the message to the tokens in the array
        $tokensToRetry = $downstreamResponse->tokensToRetry();
        if (count($tokensToRetry) > 0) {
            // dd("inside token to modify");

            foreach ($tokensToRetry as $oldToken => $newToken) {
                if(Consumer::where('token', $oldToken)->count()){
                    Consumer::where('token', $oldToken)->update(['token' => $newToken]);
                }

                if(Professional::where('token', $oldToken)->count()){
                    Professional::where('token', $oldToken)->update(['token' => $newToken]);
                }
            }
        }

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
        if($downstreamResponse->numberSuccess() > 0){
            return true;
        }else{
            false;
        }

    }


      public function sendToAll(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|string|max:500',
        ]);

        $data = [
            'content' => $request->input('content'),
        ];

        $consumerTokens = Consumer::select('token')->where('token', '!=', Null)->pluck('token')->toArray();

        $tokens = array_unique(array_merge($consumerTokens));

        foreach ($tokens as $token){
            app('App\Http\Controllers\FCMController')->sendNotification('Notification From Admin',$request->content, [], $token);
        }

        /*$optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder("Notification From Riona Admin");
        $notificationBuilder->setBody($request->content)->setSound('default');


        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        if(count($tokens) ==  0)
        {
            \Log::info('no tokens found');
            return redirect()->route('adminDashboard');
        }

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();


        // return Array - you must remove all this tokens in your database
        $tokensToDelete = $downstreamResponse->tokensToDelete();
        if (count($tokensToDelete) > 0) {
            Consumer::whereIn('token', $tokensToDelete)->update(['token' => NULL]);
            Professional::whereIn('token', $tokensToDelete)->update(['token' => NULL]);
        }

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $tokensToModify = $downstreamResponse->tokensToModify();
        if (count($tokensToModify) > 0) {
            foreach ($tokensToModify as $oldToken => $newToken) {
                if(Consumer::where('token', $oldToken)->count()){
                    Consumer::where('token', $oldToken)->update(['token' => $newToken]);
                }

                if(Professional::where('token', $oldToken)->count()){
                    Professional::where('token', $oldToken)->update(['token' => $newToken]);
                }
            }
        }

        // return Array - you should try to resend the message to the tokens in the array
        $tokensToRetry = $downstreamResponse->tokensToRetry();
        if (count($tokensToRetry) > 0) {
            foreach ($tokensToRetry as $oldToken => $newToken) {
                if(Consumer::where('token', $oldToken)->count()){
                    Consumer::where('token', $oldToken)->update(['token' => $newToken]);
                }

                if(Professional::where('token', $oldToken)->count()){
                    Professional::where('token', $oldToken)->update(['token' => $newToken]);
                }
            }
        }

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();*/
        return redirect()->route('adminDashboard');
    }
}
