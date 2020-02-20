<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class LoginTest extends DuskTestCase
{
    // Dusk実行前にマイグレーションする
    use DatabaseMigrations;

    /**
     * ログイン機能をテストする
     *
     * @return void
     */
    public function testLogin()
    {
        // ユーザーを作成しておく
        $user = factory(User::class)->create([
          'email' => 'dusk@foo.com',
          'password' => bcrypt('123456'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login') //ログインページへ移動
                    ->type('email', $user->email) //メールアドレスを入力
                    ->type('password', '123456') //パスワードを入力
                    ->press('Login') //送信ボタンをクリック
                    ->assertPathIs('/users/'.$user->id); //プロフィールへ移動していることを確認
        });
    }
}
