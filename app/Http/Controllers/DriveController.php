<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriveController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setAuthConfig(storage_path() . '/drive/' . 'client_id.json');
        $this->client->addScope(\Google_Service_Sheets::SPREADSHEETS_READONLY);
        $this->client->addScope(\Google_Service_Drive::DRIVE_READONLY);
        $this->client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback');
    }

    public function singleFile(Request $request, $file_id)
    {
        if (!$this->checkToken()) return $this->auth();
       /* $service = new \Google_Service_Sheets($this->client);
        $spreadsheetId = $file_id;
        $range = 'Class Data!A1:F10';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();*/

        $drive_service = new \Google_Service_Drive($this->client);
        $content = $drive_service->files->export($file_id, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        return $content;
        //return view('drive.files.single', ['rows' => $values]);
    }

    public function listDriveContents(Request $request)
    {
        if (!$this->checkToken()) return $this->auth();
        $drive_service = new \Google_Service_Drive($this->client);
        $files_list = $drive_service->files->listFiles(array())->getFiles();

        $files_list = array_filter($files_list, function($k) {
            return strpos($k['name'], "Kahoot") !== false && $k['mimeType'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        });

        return view('drive.files.list', ["files_list" => $files_list]);
    }

    public function auth()
    {
        $auth_url = $this->client->createAuthUrl();
        return redirect(filter_var($auth_url, FILTER_SANITIZE_URL));
    }

    public function authcallback(Request $request)
    {
        $this->client->authenticate($request->code);
        $token = \App\DriveToken::create($this->client->getAccessToken());
        $token->expire_time = $token->created + $token->expires_in;
        $token->save();
        return redirect()->action('DriveController@listDriveContents');
    }

    private function checkToken()
    {
        $token = \App\DriveToken::where('expire_time', '>', time())->first();
        if (is_null($token))
            return false;
        $this->client->setAccessToken($token->getJson());
        return true;
    }
}
