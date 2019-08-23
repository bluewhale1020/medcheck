@extends('layouts.doc')

@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">検査エリア管理</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>検査エリア一覧画面</h5>
        <p class="lead">登録した検査エリアが一覧で表示されます。</p>
        <img src="{{asset('img/pages/manage_area.png')}}" alt="エリア一覧">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>検査エリアを追加すると左メニューの「検査エリア」が更新されます。</p>
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
                            <a class="nav-link" id="v-pills-create-tab" data-toggle="pill" href="#v-pills-create" role="tab" aria-controls="v-pills-create" aria-selected="false">新規作成</a>
                            <a class="nav-link" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="false">編集</a>
                            <a class="nav-link" id="v-pills-delete-tab" data-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">削除</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                                <p class="lead">検査エリアを検索します</p>
                                <p>Search欄に検索キーを入力して「Enter」を押すと一覧に検索結果が表示されます。</p>
                                <div class="callout callout-info mt-3">
                                    <h5 class="text-bold">Tip!</h5>            
                                    <p>バーガーボタンを押すと、詳細検索パネルが開き、さらに検索条件を追加できます。</p>
                                    <div class="card">
                                            <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="img-fluid img-rectangle" src="{{asset('img/pages/search_area.png')}}" alt="検査エリアの検索">                                                                  
                                            </div>
                                    </div></div>                                     
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-create" role="tabpanel" aria-labelledby="v-pills-create-tab">
                                    <div class="row">
                                            <div class="col-6">
                                                <div class="card">
                                                        <div class="card-body box-profile">
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/create_area.png')}}" alt="検査エリア作成フォーム">                                                                  
                                                        </div>
                                                </div></div>
                                            </div>
                                            <div class="col-6">
                                                <p class="lead">検査エリアを新規に作成します</p>
                                                <ol>
                                                    <li>右上の「新規エリア追加」ボタンを押す</li>
                                                    <li>基本事項・エリアの画像・検査項目・関連する役職を入力</li>
                                                    <li>「登録」ボタンを押して新規作成</li>
                                                    <li>一覧リストが更新される</li>
                                                </ol>                   
                                            
                                            </div>
                                    </div>                 
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>作成したエリアがメニュー項目に現れるのは関連する役職のユーザーのみになります。</p>
                                </div>  
                            </div>
                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                <div class="row">
                                    <div class="col-6">
                                            <div class="card">
                                                    <div class="card-body box-profile">
                                                    <div class="text-center">
                                                        <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_area.png')}}" alt="検査エリア編集フォーム">                                                                  
                                                    </div>
                                            </div></div>
                                    </div>
                                    <div class="col-6">
                                        <p class="lead">検査エリアを編集します</p>
                                        <ol>
                                            <li>編集したい列右端の編集アイコンのボタンを押す</li>
                                            <li>エリア情報を変更する</li>
                                            <li>「更新」ボタンを押す</li>
                                            <li>一覧リストが更新される</li>
                                        </ol>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                                <p class="lead">検査エリアを削除します</p>
                                <ol>
                                    <li>削除したい列左端の削除アイコンのボタンを押す</li>
                                    <li>指定の検査エリアが削除される</li>
                                    <li>一覧リストが更新される</li>
                                </ol> 
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
