@extends('layouts.doc')

@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">管理</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>一覧画面</h5>
        <p class="lead">一覧で表示されます。</p>
        <img src="{{asset('img/pages/config.png')}}" alt="設定フォーム">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p></p>
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
                                <p>を検索します。</p>
                                <p>Search欄に検索キーを入力して「Enter」を押すと一覧に検索結果が表示されます。</p>
                                <div class="callout callout-info mt-3">
                                    <h5 class="text-bold">Tip!</h5>            
                                    <p>バーガーボタンを押すと、詳細検索パネルが開き、さらに検索条件を追加できます。</p>
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-create" role="tabpanel" aria-labelledby="v-pills-create-tab">
                                <p>を新規に作成します。</p>
                                <ol>
                                    <li>右上の「新規」ボタンを押す</li>
                                    <li>名前・役柄・Email・パスワードを入力する</li>
                                    <li>「登録」ボタンを押して新規作成</li>
                                    <li>一覧リストが更新される</li>
                                </ol>                   
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p></p>
                                </div>  
                            </div>
                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                <p>編集します。</p>
                                <ol>
                                    <li>編集したい列右端の「編集」ボタンを押す</li>
                                    <li>を変更する</li>
                                    <li>「更新」ボタンを押す</li>
                                    <li>一覧リストが更新される</li>
                                </ol>
                            </div>
                            <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                                <p>を削除します。</p>
                                <ol>
                                    <li>編集したい列右端の「削除」ボタンを押す</li>
                                    <li>指定のが削除される</li>
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
