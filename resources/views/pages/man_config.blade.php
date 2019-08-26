@extends('layouts.doc')
@section('menu','config')
@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">システム設定</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>設定画面</h5>
        <p class="lead">設定項目をフォームで表示します。</p>
        <img src="{{asset('img/pages/config.png')}}" alt="設定フォーム">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>インポートしたデータがシステムに反映されていない場合は、現在使用中の健診簿を変更します。</p>
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
                            <a class="nav-link active" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="true">編集</a>
                            <a class="nav-link" id="v-pills-desc-tab" data-toggle="pill" href="#v-pills-desc" role="tab" aria-controls="v-pills-desc" aria-selected="false">項目詳細</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">

                            <div class="tab-pane fade show active" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">
                                <p class="lead">設定内容を編集します。</p>
                                <ol>
                                    <li>編集したい項目を変更する</li>
                                    <li>「変更の保存」ボタンを押す</li>
                                </ol>
                            </div>
                            <div class="tab-pane fade" id="v-pills-desc" role="tabpanel" aria-labelledby="v-pills-desc-tab">
                                <p class="lead">設定項目の説明</p>
                                <table class="table table-bordered table-condensed">
                                    <thead>
                                        <tr>
                                            <th>項目名</th><th>内容</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>現在使用中の健診簿</td>
                                            <td>インポートデータはそれぞれの健診簿に紐づけられています。健診簿を選ぶことで、健診の対象となるデータを切り替えます。</td>
                                        </tr>
                                        <tr>
                                            <td>通番開始番号</td>
                                            <td>受付処理での通番の最初の番号を設定します。</td>
                                        </tr>
                                        <tr>
                                            <td>バーコード受信方法</td>
                                            <td>バーコードリーダーの受信方法を設定します。（現在USB(HID)のみ対応）</td>
                                        </tr>
                                        <tr>
                                            <td>バーコードカラム名</td>
                                            <td>バーコードデータと対応する受診者データのカラム名</td>
                                        </tr>
                                        <tr>
                                            <td>バーコードカラム名2</td>
                                            <td>バーコードデータと対応する受診者データのカラム名 <br>
                                                （バーコードカラム名を優先）</td>
                                        </tr>
                                        <tr>
                                            <td>白紙バーコードNo</td>
                                            <td>白紙の受診票のバーコード番号。読み込むと新規作成フォームが表示されます。</td>
                                        </tr>
                                    </tbody>
                                </table>
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
