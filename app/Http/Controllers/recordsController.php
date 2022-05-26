<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Record;
use App\Models\Genre;
use App\Models\AlbumGenre;

class recordsController extends Controller
{
    public function recordsAll()
    {
        return view("records", ['records' => Record::join('artists', 'records.artist', '=', 'artists.id_artist')
            ->join('albums', 'records.album', '=', 'albums.id_albums')->take(16)->get()]);
    }

    public function checkboxes(Request $request)
    {
        $genre = $request->validate([
            'genre' => 'required'
        ])['genre'];


        $price = $request->session()->get('price');

        $idRecord = array();
        foreach ($price as $record) {
            $idRecord[] = $record->id;
        }
        if(isset($idRecord)) {
            $idRecord = Record::select('id')->get();
        }


        $idGenreTable = Genre::whereIn('genreName', $genre)->get();
        $id = array();
        foreach ($idGenreTable as $item) {
            $id[] = $item->id;
        }

        $idAlbum = AlbumGenre::whereIn('genreId', $id)->get();
        unset($id);
        foreach ($idAlbum as $item) {
            $id[] = $item->albumId;
        }
        if(!isset($id)) {
            $id[] = 0;
        }

        $checkbox = Record::join('albums', 'records.album', '=', 'albums.id_albums')
            ->join('artists', 'records.artist', '=', 'artists.id_artist')
            ->whereIn('albums.id_albums', array_unique($id))
            ->get();

        session(['checkboxes' => $checkbox]);



        return view("records", ['records' => Record::join('albums', 'records.album', '=', 'albums.id_albums')
            ->join('artists', 'records.artist', '=', 'artists.id_artist')
            ->whereIn('albums.id_albums', array_unique($id))
            ->whereIn('records.id', $idRecord)
            ->get()]);

    }

    public function price(Request $request)
    {
        $start = $request->input("start");

        $end = $request->input("end");

        $checkbox = $request->session()->get('checkboxes');

        $idRecord = array();
        foreach ($checkbox as $record) {
            $idRecord[] = $record->id;
        }
        if(count($idRecord) <=0) {
            $idRecord = Record::select('id')->get();
        }


        if (isset($start) and isset($end)) {

            $priceCheckbox = Record::join('albums', 'records.album', '=', 'albums.id_albums')
                ->join('artists', 'records.artist', '=', 'artists.id_artist')
                ->where('price', '<', $end)
                ->where('price', '>', $start)
                ->get();

            session(['price' => $priceCheckbox]);

            return view("records", ['records' => Record::join('albums', 'records.album', '=', 'albums.id_albums')
                ->join('artists', 'records.artist', '=', 'artists.id_artist')
                ->where('price', '<', $end)
                ->where('price', '>', $start)
                ->whereIn('records.id', $idRecord)
                ->get()]);
        }


        if (isset($end)) {
            $priceCheckbox = Record::join('albums', 'records.album', '=', 'albums.id_albums')
                ->join('artists', 'records.artist', '=', 'artists.id_artist')
                ->where('price', '<', $end)
                ->get();

            session(['price' => $priceCheckbox]);


            return view("records", ['records' => Record::join('albums', 'records.album', '=', 'albums.id_albums')
                ->join('artists', 'records.artist', '=', 'artists.id_artist')
                ->where('price', '<', $end)
                ->whereIn('records.id', $idRecord)
                ->get()]);
        }


        $priceCheckbox = Record::join('albums', 'records.album', '=', 'albums.id_albums')
            ->join('artists', 'records.artist', '=', 'artists.id_artist')
            ->where('price', '>', $start)
            ->get();

        session(['price' => $priceCheckbox]);

        return view("records", ['records' => Record::join('albums', 'records.album', '=', 'albums.id_albums')
            ->join('artists', 'records.artist', '=', 'artists.id_artist')
            ->where('price', '>', $start)
            ->whereIn('records.id', $idRecord)
            ->get()]);

    }
}
