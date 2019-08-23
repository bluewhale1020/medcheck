@extends('layouts.doc')

@section('content')
<div class="container">
    <div class="row">
    <div class="content-header mt-3">
            <h1 class="text-dark">予約リスト</h1>
                            
    </div>
    </div> <div class="row">
        <div class="content px-2">
        <hr>
        <div class="section mt-5">        
        <h5>予約情報一覧画面</h5>
        <p class="lead">受診待ちの受診予定者を一覧で表示します。</p>
        <img src="{{asset('img/pages/reserve_list2.png')}}" alt="予約リスト">
        <div class="callout callout-info mt-4">
                <h5 class="text-bold">Tip!</h5>    
                <p>基本的にバーコードでの受付処理を前提に作られています。バーコードリーダーで読み取る前に、必ずカーソルを<strong class="text-red">バーコード入力欄</strong>に置いてください。</p>
                <p>通番は受付ごとに自動的に連番が振られます。変更したい場合は通番入力欄に希望の番号を入力してください。</p>
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
                            <a class="nav-link" id="v-pills-barcode-tab" data-toggle="pill" href="#v-pills-barcode" role="tab" aria-controls="v-pills-barcode" aria-selected="false">バーコード受付</a>
                            <a class="nav-link" id="v-pills-create-tab" data-toggle="pill" href="#v-pills-create" role="tab" aria-controls="v-pills-create" aria-selected="false">新規作成</a>
                            <a class="nav-link" id="v-pills-edit-tab" data-toggle="pill" href="#v-pills-edit" role="tab" aria-controls="v-pills-edit" aria-selected="false">編集</a>
                            <a class="nav-link" id="v-pills-delete-tab" data-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">削除</a>
                            <a class="nav-link" id="v-pills-print-tab" data-toggle="pill" href="#v-pills-print" role="tab" aria-controls="v-pills-print" aria-selected="false">印刷</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                               
                                <p class="lead">予約情報を検索します。</p>
                                <p>Search欄に検索キーを入力して「Enter」を押すと一覧に検索結果が表示されます。</p>
                                <div class="callout callout-info mt-3">
                                    <h5 class="text-bold">Tip!</h5>            
                                    <p>バーガーボタンを押すと、詳細検索パネルが開き、さらに検索条件を追加できます。</p>
                                    <div class="card">
                                            <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="img-fluid img-rectangle" src="{{asset('img/pages/search_reserve.png')}}" alt="予約情報の検索">                                                                  
                                            </div>
                                    </div></div>                                    
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="v-pills-barcode" role="tabpanel" aria-labelledby="v-pills-barcode-tab">
                                <div class="row"> 
                                <div class="col-6">
                                    <div class="card">
                                    <div class="card-body box-profile">
                                    <div class="text-center">
                                        <div class="figure">
                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_reserve.png')}}" alt="予約情報の確定">
                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_reserve2.png')}}" alt="予約情報の確定">
                                        </div>
    
                                    </div>
                                    </div></div>
                                    
                                </div>
                                <div class="col-6">
                                        <p class="lead">バーコード受付処理を行います</p>        
                                        <ol>
                                                <li>マウスのカーソルをバーコード入力欄に置く</li>
                                                <li>受診票のバーコードを読みとる</li>
                                                <li>在籍番号が一致すれば予約編集フォームが開く</li>
                                                <li>内容を確認の上、「確定」ボタンを押す</li>
                                                <li>受診者に通番が登録され、受付処理が完了する</li>
                                                <li>一覧リストが更新される</li>
                                            </ol>
                                    </div> 
                                </div>                                 


                   
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>バーコードの登録情報が見つからない場合は、新規作成フォームが開きます。</p>
                                </div>
                                
                                
                            </div>
                            <div class="tab-pane fade" id="v-pills-create" role="tabpanel" aria-labelledby="v-pills-create-tab">
                                <div class="row"> 
                                <div class="col-6">
                                    <div class="card">
                                    <div class="card-body box-profile">
                                    <div class="text-center">
                                        <div class="figure">
                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/create_reserve.png')}}" alt="予約作成フォーム">
                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/create_reserve2.png')}}" alt="予約作成フォーム">
                                        </div>
    
                                    </div>
                                    </div></div>
                                    
                                </div>
                                <div class="col-6">
                                        <p class="lead">予約情報を新規に作成します。</p>
        
                                        <ol>
                                                <li>右上の「新規予約」ボタンを押す</li>
                                                <li>個人情報・予約情報・検査項目を入力する</li>
                                                <li>「登録」ボタンを押して新規作成</li>
                                                <li>一覧リストが更新される</li>
                                            </ol>
                                    </div> 
                                </div>                                 


                   
                                <div class="callout callout-warning mt-3">
                                    <h5 class="text-bold">Info</h5>            
                                    <p>新規作成の際に同時に受付処理も完了します。</p>
                                </div>
                                
                                
                            </div>
                            <div class="tab-pane fade" id="v-pills-edit" role="tabpanel" aria-labelledby="v-pills-edit-tab">

                                    <div class="row"> 
                                            <div class="col-6">
                                                <div class="card">
                                                <div class="card-body box-profile">
                                                <div class="text-center">
                                                    <div class="figure">
                                                        <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_reserve_.png')}}" alt="予約編集フォーム">
                                                        <img class="img-fluid img-rectangle" src="{{asset('img/pages/edit_reserve2.png')}}" alt="予約編集フォーム">
                                                    </div>
                
                                                </div>
                                                </div></div>
                                                
                                            </div>
                                            <div class="col-6">
                                                    <p class="lead">予約情報を編集します。</p>
                    
                                                    <ol>
                                                            <li>編集したい列左端の編集アイコンのボタンを押す</li>
                                                            <li>変えたい項目を変更する</li>
                                                            <li>「確定」ボタンを押す</li>
                                                            <li>一覧リストが更新される</li>
                                                        </ol>
                                                </div> 
                                            </div> 
                               
                                            <div class="callout callout-warning mt-3">
                                                <h5 class="text-bold">Info</h5>            
                                                <p>検査項目は、すでに実施済みのものは変更できません！</p>
                                            </div>

                            </div>
                            <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">
                                <p class="lead">予約情報を削除します。</p>
                                <ol>
                                    <li>編集したい列左端の削除アイコンボタンを押す</li>
                                    <li>指定の予約情報が削除される</li>
                                    <li>一覧リストが更新される</li>
                                </ol> 
                            </div>

                            <div class="tab-pane fade" id="v-pills-print" role="tabpanel" aria-labelledby="v-pills-print-tab">
                                    <div class="px-3">
                                            <div class="">
                                                <div class="card">
                                                        <div class="card-body box-profile">
                                                        <div class="text-center">
                                                            <img class="img-fluid img-rectangle" src="{{asset('img/pages/print_reserve.png')}}" alt="健診簿">                                                                  
                                                        </div>
                                                </div></div>
                                            </div>
                                            <div class="">
                                                <p class="lead">予約データをエクセルファイルで出力します</p>
                                                <ol>
                                                    <li>右上の「健診簿出力」ボタンを押す</li>
                                                    <li>検索した受診者の予約情報がエクセルファイルで出力される</li>
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
