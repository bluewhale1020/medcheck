@extends('layouts.doc')
@section('menu','area')
@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">検査エリア</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>対象者一覧画面</h5>
        <p class="lead">選択した検査エリアの対象者を一覧で表示します。</p>
        <img src="{{asset('img/pages/area.png')}}" alt="対象者一覧">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>状況カラムには、各受診者の受診状況が表示されます。検査項目ではタイプによって、結果が表示される場合と、未受診・受診が<span class="text-red">●</span>と<span class="text-green">✔</span>で表示されるものがあります。</p>
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
                            <a class="nav-link" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="false">結果入力</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                                <p class="lead">受診者を検索します</p>
                                <p>通番欄に通番を、またはSearch欄に検索キーを入力して「Enter」を押すと一覧に検索結果が表示されます。</p>
                                <div class="callout callout-info mt-3">
                                    <h5 class="text-bold">Tip!</h5>            
                                    <p>バーガーボタンを押すと、詳細検索パネルが開き、さらに検索条件を追加できます。</p>
                                    <div class="card">
                                            <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="img-fluid img-rectangle" src="{{asset('img/pages/search_result.png')}}" alt="受診者の検索">                                                                  
                                            </div>
                                    </div></div>                                     
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                    <div class="row">
                                            <div class="col-6">
                                                <div class="card">
                                                        <div class="card-body box-profile">
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_result.png')}}" alt="検査結果入力フォーム">                                                                  
                                                        </div>
                                                </div></div>
                                            </div>
                                            <div class="col-6">
                                                    <p class="lead">検査結果を入力します</p>
                                                    <ol>
                                                        <li>結果を入力したい列左端の編集アイコンのボタンを押す</li>
                                                        <li>結果データを入力する</li>
                                                        <li>「更新」ボタンを押す</li>
                                                        <li>一覧リストが更新される</li>
                                                    </ol>                 
                                            
                                            </div>
                                    </div>                 
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>検査項目によって、テキスト入力欄のものとチェックボックスのものがあります。入力データが正常範囲を超えると警告表示が現れます。</p>
                                    <p>「全て✔」ボタンはフォームのチェックボックスの全てをチェックし、「すべて正常」ボタンはテキスト入力欄全てに「所見なし」と入力します。</p>
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
