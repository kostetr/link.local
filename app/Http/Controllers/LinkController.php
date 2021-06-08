<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Link;

class LinkController extends Controller {

    private $data = [];

    public function __construct() {
        $this->data['url'] = $this->url();
    }

    /**
     * Возвращает доменное имя
     *     * 
     */
    public function url() {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function index() {
        if (isset(Auth::user()->id)) {
            $this->data['links'] = Link::where('user_id', Auth::user()->id)->get();
        }

        return view('index', $this->data);
    }

    public function save(Request $request) {
        $request->validate([
            'long_link' => 'required',
            'life_time_minutes' => 'required|numeric||min:0|not_in:0',
        ]);
        do {
            $key = Str::random(25);
        } while (count(Link::where('key', $key)->get()) > 0);



        if (!Link::create(array_merge($request->all(), ['user_id' => Auth::user()->id, 'key' => $key, 'transitions' => 0]))) {
            return redirect()->withErrors(['Ошибка записи в БД']);
        }
        $link = Link::where('key', $key)->get();

        DB::unprepared('CREATE EVENT event_name_id' . $link[0]->id . ' ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL ' . $link[0]->life_time_minutes . ' MINUTE DO DELETE FROM `links` WHERE ID=' . $link[0]->id . ';');
        return redirect('/')->with('success', 'Все Ок. Линк добавлен!');
    }

    public function update($key) {
        if (Link::where('key', $key)->increment('transitions')) {
            $link = Link::where('key', $key)->first();
            return redirect($link['long_link']);
        }
    }

    public function destroy($id) {
        //
    }

}
