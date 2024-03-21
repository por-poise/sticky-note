##　メモ

* .env: 環境毎の設定情報等をもってる
* routes/web.php: パスルーティング
* `php artisan make:controller -r ***Controller`

## DB接続
`mysql -uroot -h 127.0.0.1 -p`

* .envのDB_HOSTはdocker-composeのservice nameを指定する

## トラシュー

* `Add [name] to fillable property to allow mass assignment on [Illuminate\Foundation\Auth\User].`
  * fillableを設定しているのは自分が作ったUserクラスであって、ファザードの方ではない。useで自分のクラスを指定してあげればOK
* `php artisan migrate`での`SQLSTATE[HY000] [2002] php_network_getaddresses: getaddrinfo for db failed: nodename nor servname provided, or not known`
  * migrateはappのcontainerから実行する必要がある `docker-compose exec app bash` -> `app artisan tinker`
* モデルの保存時に`SQLSTATE[HY000]: General error: 1364 Field 'user_id' doesn't have a default value`
  * Modelクラスでfillableに指定していなければフロントからもtinkerからも値がDBに渡らないから。fillableカラムを追加すればOK

## アプリ設計
1. タスク管理:★ph.1
- タスクの追加、編集、削除ができる。
- 各タスクに対して期限日（デッドライン）を設定できる。
- タスクの進行状況（未着手、進行中、完了）をマークできる。
- 優先度別にタスクを並べ替えられる。
2. カレンダー:★ph.1
- タスクの期限日をカレンダーに自動で反映させる。
- 引っ越し関連のイベント（不用品回収日、引っ越し業者との打ち合わせ等）をカレンダーに追加できる。
3. チェックリスト:
- 引っ越し準備のための標準的なチェックリストを提供。
- ユーザーがカスタマイズ可能なチェックリストを作成できる。
4. 予算管理:
- 引っ越しにかかる予想コストを記録。
- 実際の支出を追跡して予算オーバーを防ぐ。
5. 重要書類の管理:
- 引っ越しに必要な書類（契約書、見積もり書等）をデジタル化してアプリ内で管理。
- 書類にアクセスしやすいように分類。
6. リマインダー機能:
- 重要なタスクやイベントが近づいている時に通知を送る。
- 最終確認事項のリマインド。
7. 引っ越し業者の情報管理:
- 見積もり、連絡先、サービス内容を記録。
- 業者選定のための比較機能。
8. 物品のインベントリ管理:
- 引っ越し前に家財のリストアップ。
- 物品のカテゴリ別管理や部屋別管理。
9. 共有機能:
- 家族やルームメイトとタスクや進行状況を共有。
- タスクの割り当てや進捗の共有。
10. ユーザーフレンドリーなインターフェース:→非機能要件
- シンプルで直感的な操作性。
- ユーザーのニーズに応じてカスタマイズ可能な設定。
