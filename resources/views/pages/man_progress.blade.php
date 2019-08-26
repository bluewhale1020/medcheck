@extends('layouts.doc')
@section('menu','progress')
@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">検査進捗管理</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>受診者の進捗状況一覧画面</h5>
        <p class="lead">受付済みの受診者の進捗状況が一覧で表示されます。</p>
        <img src="{{asset('img/pages/progress.png')}}" alt="進捗状況一覧">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>各受診者の進捗度が青いバーで示されます。各検査項目では、<span class="text-red">●</span>が未受診、<span class="text-green">✔</span>が受診済を表します。</p>
            </div> 
        </div>  
        <br>       
            <hr>
        <div class="section mt-5">
        <h5>主な機能</h5>

        <div class="row">

                <div class="card card-body">
                    <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-search-tab" data-toggle="pill" href="#v-pills-search" role="tab" aria-controls="v-pills-search" aria-selected="true">検索</a>
                            <a class="nav-link" id="v-pills-print-tab" data-toggle="pill" href="#v-pills-print" role="tab" aria-controls="v-pills-print" aria-selected="false">印刷</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                                <p class="lead">受診者を検索します</p>
                                <p>Search欄に検索キーを入力して「Enter」を押すと一覧に検索結果が表示されます。</p>
                                <div class="callout callout-info mt-3">
                                    <h5 class="text-bold">Tip!</h5>            
                                    <p>バーガーボタンを押すと、詳細検索パネルが開き、さらに検索条件を追加できます。</p>
                                    <div class="card">
                                            <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="img-fluid img-rectangle" src="{{asset('img/pages/search_progress.png')}}" alt="受診者の検索">                                                                  
                                            </div>
                                    </div></div>                                     
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-print" role="tabpanel" aria-labelledby="v-pills-print-tab">
                                    <div class="px-3">
                                            <div class="">
                                                <div class="card">
                                                        <div class="card-body box-profile">
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/print_progress.png')}}" alt="進捗状況表">                                                                  
                                                        </div>
                                                </div></div>
                                            </div>
                                            <div class="">
                                                <p class="lead">進捗状況データをエクセルファイルで出力します</p>
                                                <ol>
                                                    <li>右上の「進捗状況データ出力」ボタンを押す</li>
                                                    <li>検索した受診者の進捗状況のデータをエクセルファイルで出力される</li>
                                                </ol>                   
                                            
                                            </div>
                                    </div>                 
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>特定のデータを出力したい場合は、まず検索・絞り込みで条件を指定して一覧を更新してから出力します。</p>
                                </div>  
                            </div>

                        </div>
                    </div>
                </div>
                </div>

        </div>
        </div>

    </div>    
    
    
    </div>
</div>
@endsection
