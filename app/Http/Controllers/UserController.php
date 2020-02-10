<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//modelをインポート
use App\User;
use App\Http\Requests\StoreUser;

class UserController extends Controller
{
  /**
   * 各アクションの前に実行させるミドルウェア
   */
  public function __construct()
  {
    // $this->middleware('auth')->except(['index', 'show']);
    // 登録完了していなくても退会だけはできるようにする
    $this->middleware('auth')->only('destroy');
    $this->middleware('verified')->except(['index', 'show', 'destroy']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  //ユーザーの一覧表示
  public function index()
  {
    $users = User::paginate(5);  //すべてのユーザーを5件ずつ表示
    return view('users.index', ['users' => $users]);
    //第一引数には"遷移先のbladeファイル名", 第二引数は"['渡す先での変数名' => 今回渡す変数]"
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  // 追加用のフォームへ移動
  public function create()
  {
    return view('users.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\StoreUser  $request
   * @return \Illuminate\Http\Response
   */
  // 実際の追加処理
  // 完了後、作成したユーザーのページへ移動
  public function store(StoreUser $request)
  {
    $user = new User;  //新しいインスタンスを作成
    $user->name = $request->name;  //それぞれの値を保存して
    $user->email = $request->email;
    $user->password = $request->password;
    $user->save();  //DBに保存
    return redirect('users/' . $user - id);  //ユーザー詳細ページへ移動
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  //ユーザー1件詳細表示
  public function show(User $user)
  //「モデル結合ルート」を利用して簡潔に記述するため、引数は$idではなくUser $userとする。
  {
    // そのユーザーが投稿した記事のうち、最新5件を取得
    $user->posts = $user->posts()->paginate(5);
    return view('users.show', ['user' => $user]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // 更新用フォームへ移動
  public function edit(User $user)
  {
    $this->authorize('edit', $user);
    return view('users.edit', ['user' => $user]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // 実際の更新処理
  // 完了後、更新したユーザーのページへ移動
  public function update(Request $request, User $user)
  {
    $this->authorize('edit', $user);

    // name欄だけを検査するため、元のStoreUserクラス内のバリデーション・ルールからname欄のルールだけを取り出す。
    $request->validate([
      'name' => (new StoreUser())->rules()['name']
    ]);

    $user->name = $request->name;
    $user->save();
    return redirect('users/' . $user->id);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  // 一件削除
  public function destroy(User $user)
  {
    $this->authorize('edit', $user);
    $user->delete();
    return redirect('users');
  }
}
