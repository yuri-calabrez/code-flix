<?php

namespace CodeFlix\Http\Controllers\Admin;

use CodeFlix\Contracts\Repositories\SerieRepository;
use CodeFlix\Forms\SerieForm;
use CodeFlix\Models\Serie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use CodeFlix\Http\Controllers\Controller;

class SeriesController extends Controller
{
    /**
     * @var SerieRepository
     */
    private $repository;

    public function __construct(SerieRepository $repository)
    {

        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $series = $this->repository->orderBy('id', 'DESC')->paginate();
        return view('admin.series.index', compact('series'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = \FormBuilder::create(SerieForm::class, [
            'url' => route('admin.series.store'),
            'method' => 'POST'
        ]);
        return view('admin.series.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = \FormBuilder::create(SerieForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $data = $form->getFieldValues();
        $data['thumb'] = env('SERIE_NO_THUMB');
        Model::unguard();
        $this->repository->create($data);
        $request->session()->flash('success', 'Série criada com sucesso!');
        return redirect()->route('admin.series.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \CodeFlix\Models\Serie  $series
     * @return \Illuminate\Http\Response
     */
    public function show(Serie $series)
    {
        return view('admin.series.show', ['serie' => $series]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \CodeFlix\Models\Serie  $series
     * @return \Illuminate\Http\Response
     */
    public function edit(Serie $series)
    {
        $form = \FormBuilder::create(SerieForm::class, [
            'url' => route('admin.series.update', ['serie' => $series->id]),
            'method' => 'PUT',
            'model' => $series
        ]);
        return view('admin.series.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \CodeFlix\Models\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form = \FormBuilder::create(SerieForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $data = $form->getFieldValues();
        $this->repository->update($data, $id);
        $request->session()->flash('success', "Série <b>{$data['title']}</b> editada com sucesso!");
        return redirect()->route('admin.series.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \CodeFlix\Models\Serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        \Session::flash('success', 'Série removida com sucesso!');
        return redirect()->route('admin.series.index');
    }

    public function thumbAsset(Serie $serie)
    {
        return response()->download($serie->thumb_path);
    }

    public function thumbSmallAsset(Serie $serie)
    {
        return response()->download($serie->thumb_small_path);
    }
}
