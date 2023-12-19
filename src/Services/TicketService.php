<?php
namespace Netweb\Tickets\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public $client_code;
    public $project_code;
    public $labels;
    public $priorities;
    public $stages;
    public $users;
    public $clients;
    public function __construct(){
        $this->client_code = config('tickets.client_code');
        $this->project_code = config('tickets.project_code');
        $res = Http::get(config('tickets.api_url').'/get-all-statuses');
        if($res->successful()){
            $this->labels = (isset($res->json()['labels']))? $res->json()['labels'] : [];
            $this->priorities = (isset($res->json()['priorities']))? $res->json()['priorities'] : [];
            $this->stages = (isset($res->json()['stages']))? $res->json()['stages'] : [];
            $this->users = (isset($res->json()['users']))? $res->json()['users'] : [];
            $this->clients = (isset($res->json()['clients']))? $res->json()['clients'] : [];
        }else{
            $this->labels = [];
            $this->priorities = [];
            $this->stages = [];
            $this->users = [];
            $this->clients = [];
        }
    }

    public function ticketGenerate($request){
        if(!isset($request->title) || !isset($request->description) || $request->title == null || $request->description == null){
            return ['status' => false,'message' => 'Please Fill all Fields'];
        }
        $data = $request->only('title','description','label_id','priority_id');
        $data['client_code'] = $this->client_code;
        $data['project_code'] = $this->project_code;
        $files = $request->file('files');
        $fileRes = $this->fileUpload($files);
        if($fileRes['status'] == false){
            return $fileRes;
        }

        $data['files'] = $fileRes['file_names'] ?? [];
        $response =  Http::post(config('tickets.api_url').'/addTicket',$data);
        if($response->successful()){
            return $response->json();
        }
        return ['status' => false,'message' => 'Something Went Wrong','code' => 400];
    }

    public function tickets(){
        $labels = $this->labels;
        $priorities = $this->priorities;
        return view('tickets::create',compact('labels','priorities'));
    }
    public function viewTickets(){
        $labels = $this->labels;
        $priorities = $this->priorities;
        $stages = $this->stages;
        $res = Http::get(config('tickets.api_url').'/all-tickets',['client_code' => $this->client_code,'project_code' => $this->project_code]);
        if($res->successful()){
            $tickets = (isset($res->json()['tickets']))? $res->json()['tickets'] : [];
            return view('tickets::index',compact('tickets','labels','priorities','stages'));
        }
        return abort(403,'Something Went Wrong');
    }
    public function ticketDetail($id){
        $ticketId = decrypt($id);
        if($ticketId == null){
            return abort(403,'ID NOt Found');
        }
        $labels = $this->labels;
        $priorities = $this->priorities;
        $stages = $this->stages;
        $users = $this->users;
        $clients = $this->clients;
        $res = Http::get(config('tickets.api_url').'/ticket-detail',['id' => $ticketId]);
        if($res->successful()){
            $ticket = isset($res->json()['ticket'])? $res->json()['ticket'] : null;
            $comments = isset($res->json()['comments'])? $res->json()['comments'] : null;
            $attachments = isset($res->json()['attachments'])? $res->json()['attachments'] : null;
            return view('tickets::show',compact('ticket','comments','labels','priorities','stages','users','clients','attachments'));
        }
        return abort(403,'Something Went Wrong');
    }
    public function ticketComment($request){
        $data = $request->only('ticket_id','comment');
        $data['client_code'] = $this->client_code;
        $res = Http::post(config('tickets.api_url').'/ticket-comments', $data);
        if($res->successful()){
            return $res->json();
        }
        return response(['status' => false,'message' => 'Something Went Wrong'],400);
    }
    public function ticketsPagination($request){
        $labels = $this->labels;
        $priorities = $this->priorities;
        $stages = $this->stages;
        $apiUrl = $request->api_url;
        $res = Http::get($apiUrl.'&client_code='.$this->client_code.'&project_code='.$this->project_code);
        if($res->successful()){
            $tickets = $res->json()['tickets'];
            return view('tickets::pagination',compact('tickets','labels','priorities','stages'));
        }
        return false;
    }
    public function fileUpload($files)
    {
        $fileNames = [];
        foreach ($files as $file) {
            $fileContent = file_get_contents($file->getRealPath());
            $fileName = $this->generateFileName($file->getClientOriginalName());
            $res = $this->moveFileSftp($fileName,$fileContent);
            if($res['status'] == false){
                return $res;
            }
            $fileNames[] = $fileName;
        }
        return ['status' => true,'message' => 'File uploaded','code' => 200,'file_names' => $fileNames];
    }
    public function moveFileSftp($fileName,$fileContent){
        try {
            // Move the file
            Storage::disk('sftp')->put($fileName, $fileContent);

            return ['status' => true,'message' => 'File uploaded','code' => 200];
        } catch (\Exception $e) {
            return ['status' => false,'message' => $e->getMessage(),'code' => 400];
        }
    }
    public function generateFileName($name){
        $name = str_replace([' ', '&', '|'], ['', '_and_', '_or_'], $name);
        $appName = env('APP_NAME');
        $appName = Str::of($appName)->replace([' ', ''], '')->replace('&', 'and')->replace('|', '_or_')->lower()->value;
        $name = $appName.time().rand(9,99).$name;
        return strtolower($name);
    }
}
?>
