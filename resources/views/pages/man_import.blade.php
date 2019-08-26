@extends('layouts.doc')
@section('menu','import')
@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">健診簿インポート</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>一覧画面</h5>
        <p class="lead">画面上部にファイルをドロップすると、画面下部にインポートデータがテーブル表示されます。</p>
        <img src="{{asset('img/pages/import.png')}}" alt="ファイルインポート画面">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>健診簿の書式はあらかじめ決められたものを使用します。詳細についてはシステム管理者まで。</p>
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
                            <a class="nav-link active" id="v-pills-fileselect-tab" data-toggle="pill" href="#v-pills-fileselect" role="tab" aria-controls="v-pills-fileselect" aria-selected="true">ファイルの選択</a>
                            <a class="nav-link" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="false">編集</a>
                            <a class="nav-link" id="v-pills-delete-tab" data-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">削除</a>
                            <a class="nav-link" id="v-pills-import-tab" data-toggle="pill" href="#v-pills-import" role="tab" aria-controls="v-pills-import" aria-selected="false">インポート</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-fileselect" role="tabpanel" aria-labelledby="v-pills-fileselect-tab">
                                <p class="lead">インポートするファイルを選択します。</p>
                                <ol>
                                    <li>インポートフォームの枠内にファイルをドロップするか、健診簿「選択」ボタンを押してファイルを選択</li>
                                    <li>ファイルのデータがインポートデータに一覧で表示</li>

                                </ol>                                     
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>所定の書式以外はインポートできません。</p>
                                </div> 
                            </div>

                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                <p class="lead">インポートデータ一覧を編集します。</p>
                                <ol>
                                    <li>編集したいセルをクリック</li>
                                    <li>テキストボックス内の文字列を編集</li>
                                    <li>「ENTER」キーを押す</li>
                                </ol>
                            </div>
                            <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                                <p class="lead">インポートデータの列を削除します。</p>
                                <ol>
                                    <li>削除したい列の削除カラムの削除アイコンボタンを押す</li>
                                    <li>指定の列が削除される</li>
                                </ol> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-import" role="tabpanel" aria-labelledby="v-pills-import-tab">
                                <p class="lead">インポートデータをシステムへインポートします。</p>
                                <ol>
                                    <li>インポートデータの内容を一覧で確認</li>
                                    <li>「ファイルをインポートする」ボタンを押す</li>
                                    <li>一列ずつインポート処理を行い、結果カラムに結果（<span class="text-danger">✔</span>：成功、<span class="text-success">×</span>：失敗）を表示</li>
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
