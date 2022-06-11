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
            ->join('albums', 'records.album', '=', 'albums.id_albums')->take(16)->get(),
            'checkGenre' => array(),
            'start' =>  NULL,
            'end' =>  NULL]
        );
    }

    public function checkboxes(Request $request)
    {


        $genre = $request->input("genre");

        $start = $request->input("start");

        $end = $request->input("end");

        if(isset($genre) > 0) {
            $idGenreTable = Genre::whereIn('genreName', $genre)->get();

            $idGenre = array();
            foreach ($idGenreTable as $item) {
                $idGenre[] = $item->id;
            }

            $idAlbumTable = AlbumGenre::select('albumId')->whereIn('genreId', $idGenre)->get();

            $idAlbum = array();
            foreach ($idAlbumTable as $item) {
                $idAlbum[] = $item->albumId;
            }
        }


        if (isset($start) and isset($end) and isset($genre)){
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->whereIn("records.album", $idAlbum)
                ->where('price', '<', $end)
                ->where('price', '>', $start)
                ->get();
        }

        elseif (isset($start) and isset($genre)) {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->whereIn("records.album", $idAlbum)
                ->where('price', '>', $start)
                ->get();
        }

        elseif (isset($end) and isset($genre)) {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->whereIn("records.album", $idAlbum)
                ->where('price', '<', $end)
                ->get();
        }
        elseif (isset($start) and isset($end)){
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->where('price', '<', $end)
                ->where('price', '>', $start)
                ->get();
        }
        elseif (isset($genre)) {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->whereIn("records.album", $idAlbum)
                ->get();
        }
        elseif (isset($start)) {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->where('price', '>', $start)
                ->get();
        }
        elseif (isset($end)) {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')
                ->where('price', '<', $end)
                ->get();
        }
        else {
            $result = Record::join('artists', 'records.artist', '=', 'artists.id_artist')
                ->join('albums', 'records.album', '=', 'albums.id_albums')->take(16)->get();
        }




        return view("records", ['records' => $result,
            'checkGenre' => ($genre == NULL) ? array() : $genre,
            'start' => (isset($start)) ? $start : NULL,
            'end' => (isset($end)) ? $end : NULL
        ]);

    }
}
