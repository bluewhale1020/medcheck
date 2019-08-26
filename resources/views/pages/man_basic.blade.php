@extends('layouts.doc')
@section('menu','basic')
@section('content')
<div class="container">
    <div class="row">

        <div class="content-header mt-3">
                <h1 class="text-dark"><img src="{{ asset('img/checkup.png') }}" width="50px">Medcheck</h1>
                <p class="lead mt-3"><strong>Medcheck</strong>は主に健康診断の現場で行う業務処理をシステム化することで<br>
                    ペーパーレス化と効率向上を図る目的で開発しました。</p>                
        </div>

        <div class="content px-2">
        <hr>
        <h4>機能の一覧</h4>
        <ul>
        <li><p>ログイン認証</p>
        </li>
        <li><p>権限によるアクセス制限</p>
        </li>
        <li><p>健診簿のインポート</p>
        <ul>
        <li>健診簿ファイルをドラッグドロップでテーブルにデータ表示</li>
        <li>テーブルデータの簡易編集</li>
        <li>システムにアップロード</li>
        
        </ul>
        </li>
        <li><p>受診者の受付処理のための、予約リスト管理ページ</p>
        <ul>
        <li>受診票のバーコードでの受付処理</li>
        <li>予約の新規作成・編集・削除</li>
        <li>健診簿データの出力</li>
        
        </ul>
        </li>
        <li><p>検査進捗管理ページ</p>
        </li>
        <li><p>健診結果の出力ページ</p>
        </li>
        <li><p>検査エリアの作成・管理</p>
        </li>
        <li><p>検査エリアの対象者リストページ</p>
        <ul>
        <li>設定した検査エリアごとに、対象者を一覧表示</li>
        <li>そのエリアの検査結果を入力（正常範囲外の時のアラート表示）</li>
        
        </ul>
        </li>
        <li><p>各種統計データ</p>
        <ul>
        <li>受診状況のグラフ表示</li>
        <li>検査エリアの進捗状況</li>
        <li>システム上の実行処理をリストで表示</li>
        
        </ul>
        </li>
        <li><p>連絡ボード</p>
        <ul>
        <li>チャット機能でスタッフ同士で連絡・情報共有</li>
        
        </ul>
        </li>        
        </ul>
        <hr>
        <h4>登録済みユーザー</h4>
        <pre ><code>
        ユーザー名： admin
        パスワード： password
        役柄	   ： 管理者
        </code></pre>        
        <hr>
        <h4>役柄による利用制限</h4>
        <p>ユーザーの役柄により、ログイン後のメニューの表示項目が変わります。「管理者」なら全て表示されますが、<br> 「スタッフ」なら検査エリアの一部しか表示されません。</p>
        <hr>        
        <h4>簡単な利用方法</h4>
        <ol start='' >
        <li><p>ブラウザから<code>ドメイン名/medcheck/public/</code>か、Medcheckをインストールした場所の
            publicフォルダにアクセス</p>
        </li>
        <li><p>ログイン画面</p>
        <ul>
        <li>ユーザー名とパスワードを入力してログイン</li>
        
        </ul>
        </li>
        <li><p>システムに受診者データを一括登録する</p>
        <ul>
        <li>左のメニューで「健診簿インポート」を選択</li>
        <li>健診簿ファイルを選択</li>
        <li>「ファイルをインポートする」ボタンを押してデータをシステムにアップロード</li>
        
        </ul>
        </li>
        <li><p>受診者の受付</p>
        <p>あらかじめ受診票には、在籍番号を表すバーコードが印刷されていて、バーコードリーダーで</p>
        <p>それを読み込めることが前提です。</p>
        <ul>
        <li>左のメニューで「予約リスト」を選択</li>
        <li>バーコード欄にカーソルを置き、バーコードリーダーでバーコードを読み取る</li>
        <li>該当する受診者の予約情報フォームが表示されるので、「確定」ボタンを押して受付処理を行う</li>
        <li>受診者に通番が登録されて、該当する検査の対象者一覧に表示される</li>
        
        </ul>
        </li>
        <li><p>各検査エリアで検査実施</p>
        <ul>
        <li>担当するエリアを左のメニューから選択（例　視力検査：　検査エリア／視力）</li>
        <li>リスト上部の通番欄に受診者の通番を入力して、リストを絞り込む</li>
        <li>結果入力フォームを開いて、検査結果を入力</li>
        
        </ul>
        </li>
        <li><p>健診結果を出力</p>
        <ul>
        <li>左のメニューで「健診結果出力」を選択</li>
        <li>結果リスト右上の「結果データ出力」ボタンを押して、結果データをエクセルファイルでダウンロードする</li>
        
        </ul>
        </li>        
        </ol>        
        </ul>
        <hr>
        <h4>ライセンス (License)</h4>
        <p><strong>Medcheck</strong>は<a href='https://opensource.org/licenses/MIT'>MIT license</a>のもとで公開されています。<br>
        <strong>Medcheck</strong> is open-source software licensed under the <a href='https://opensource.org/licenses/MIT'>MIT license</a>.</p>

        </div>
    </div>
</div>
@endsection
