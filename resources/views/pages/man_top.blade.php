@extends('layouts.doc')
@section('menu','top')
@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">DashBoard</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>DashBoardの各表示機能</h5>
        <br>

        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-board-tab" data-toggle="pill" href="#custom-content-below-board" role="tab" aria-controls="custom-content-below-board" aria-selected="true">連絡ボード</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-report-tab" data-toggle="pill" href="#custom-content-below-report" role="tab" aria-controls="custom-content-below-report" aria-selected="false">活動報告</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-status-tab" data-toggle="pill" href="#custom-content-below-status" role="tab" aria-controls="custom-content-below-status" aria-selected="false">受診状況</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-content-below-area-tab" data-toggle="pill" href="#custom-content-below-area" role="tab" aria-controls="custom-content-below-area" aria-selected="false">検査エリアの進捗</a>
            </li>
        </ul>
        <div class="tab-content" id="custom-content-below-tabContent">
                <div class="tab-pane fade active show" id="custom-content-below-board" role="tabpanel" aria-labelledby="custom-content-below-board-tab">

                        <div class="card mt-3">
                            <div class="card-header">連絡ボード画面</div>
                                <div class="card-body box-profile">
                                <div class="text-center">
                                        <img src="{{asset('img/pages/top1.png')}}" alt="連絡ボード">
                                </div>
                        </div></div>

                        <div class="callout callout-info mt-4">
                                <h5 class="text-bold">Tip!</h5>    
                                <p>健診現場のシステムネットワーク内での情報交換用メッセージボードです。メッセージを送信すると時刻とユーザー名と共にメッセージが追加されます。</p>
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
                                        <a class="nav-link active" id="v-pills-create-tab" data-toggle="pill" href="#v-pills-create" role="tab" aria-controls="v-pills-create" aria-selected="true">メッセージ作成</a>
                                        <a class="nav-link" id="v-pills-delete-tab" data-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">メッセージ削除</a>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="v-pills-create" role="tabpanel" aria-labelledby="v-pills-create-tab">
                                            <p>メッセージを作成します。</p>
                                            <ol>
                                                <li>パネル下部のテキストボックスに件名と本文を入力</li>
                                                <li>「送信」ボタンを押してメッセージを送信</li>
                                                <li>連絡ボードが更新される</li>
                                            </ol>                   
                                        </div>
                                        <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                                            <p>メッセージを削除します。</p>
                                            <ol>
                                                <li>削除したいメッセージ吹き出し上部の「×」ボタンを押す</li>
                                                <li>指定のメッセージが削除される</li>
                                                <li>連絡ボードが更新される</li>
                                            </ol> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
            
                    </div>
                    </div>                        
                        

                </div>
                <div class="tab-pane fade" id="custom-content-below-report" role="tabpanel" aria-labelledby="custom-content-below-report-tab">
                        <div class="card mt-3">
                                <div class="card-header">活動報告画面</div>
                                    <div class="card-body box-profile">
                                    <div class="text-center">
                                            <img src="{{asset('img/pages/top2.png')}}" alt="活動報告">
                                    </div>
                            </div></div>  
                            
                            <div class="callout callout-info mt-4">
                                    <h5 class="text-bold">Tip!</h5>    
                                    <p>システムネットワーク内の主な活動状況をリストで表示します。各端末でデータの更新等を実行した際に、新しい活動として登録されます。</p>
                                </div> 

                </div>
                <div class="tab-pane fade" id="custom-content-below-status" role="tabpanel" aria-labelledby="custom-content-below-status-tab">
                        <div class="card mt-3">
                                <div class="card-header">受診状況画面</div>
                                    <div class="card-body box-profile">
                                    <div class="text-center">
                                            <img src="{{asset('img/pages/top3.png')}}" alt="受診状況">
                                    </div>
                            </div></div>  
                            
                            <div class="callout callout-info mt-4">
                                    <h5 class="text-bold">Tip!</h5>    
                                    <p>時間ごとの受診人数のグラフと健診の進捗バー、その他の進捗情報が表示されます。</p>
                                </div> 

                </div>
                <div class="tab-pane fade" id="custom-content-below-area" role="tabpanel" aria-labelledby="custom-content-below-area-tab">
                        <div class="card mt-3">
                                <div class="card-header">検査エリアの進捗画面</div>
                                    <div class="card-body box-profile">
                                    <div class="text-center">
                                            <img src="{{asset('img/pages/top4.png')}}" alt="検査エリアの進捗">
                                    </div>
                            </div></div> 
                            
                            <div class="callout callout-info mt-4">
                                    <h5 class="text-bold">Tip!</h5>    
                                    <p>健診の各検査エリアにおける受診人数と受診率がドーナツグラフで表示されます。</p>
                                </div> 
                </div>
              </div>


        
        

        </div>  


    </div>    
    
    
    </div>
</div>
@endsection
