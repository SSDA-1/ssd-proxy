<?php

namespace Ssda1\proxies\Http\Controllers;

use Ssda1\proxies\Models\Server;
use Ssda1\proxies\Service\ProcessLogService;

use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Отобразить список ресурсов.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:proxy-admin', ['only' => ['admin']]);
    }

    private function log($name, $description, $name_en = null, $description_en = null)
    {
        $log = new ProcessLogService();
        $log->createProcessLog($name, $description, $name_en, $description_en);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::all();
        return view('proxies::admin.servers.proxy', compact('servers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('proxies::admin.servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        try {
            Server::create($input);
        } catch (\Exception $exception) {
            $this->log(
                'Добавление сервера',
                "Ошибка! Сервер не добавлен",
                'Server addition',
                "Error! Server not added"
            );
        }

        $this->log(
            'Добавление сервера',
            "Успешно! Сервер добавлен",
            'Server addition',
            "Successful! Server added"
        );

        return redirect()->route('proxySetting')
            ->with('success', 'Новость успешно создана.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function edit(Server $server)
    {
        return view('proxies::admin.servers.edit',compact('server'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Server $server)
    {
        $input = $request->all();

        try {
            $server->update($input);
        } catch (\Exception $exception) {
            $this->log(
                'Редактирование сервера',
                "Ошибка! Сервер $server->id не обновлен",
                'Server editing',
                "Error! $server->id not updated"
            );
        }

        $this->log(
            'Редактирование сервера',
            "Успешно! Сервер $server->id обновлен",
            'Server editing',
            "Successful! $server->id has been updated"
        );

        return redirect()->route('proxySetting')
            ->with('success', 'Новость успешно создана.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Server  $server
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server)
    {
        //
    }

    public function getCountKraken(): int
    {
        return Server::all()->count();
    }
}
