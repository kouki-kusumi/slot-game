<?php

require_once('../config/config.php');

// ログアウト
if (isset($_POST['logout'])) {
    $_SESSION = array();
    $_POST = array();
}

// 開始処理
if (
    isset($_SESSION['loginUser']) && isset($_POST['bet']) &&
    isset($_POST['token']) && $_SESSION['token'] == $_POST['token']
) {
    $app = new \Controller\Slot($_SESSION['loginUser']['coin'], $_POST['bet']);
    $_SESSION['previousBet'] = $_POST['bet'];
    $app->run();
    $_SESSION['slot'] = $app->getSlot();
    $_SESSION['message'] = $app->getMessage();
    // コインの値をDBに保存
    try {
        $userModel = new \Model\User();
        $userModel->coinUpdate($app->getCoin(), $_SESSION['loginUser']['name']);
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }
    // Sessionのユーザーデータを更新
    $_SESSION['loginUser'] = $userModel->find($_SESSION['loginUser']['name']);
    // RPGパターンで二重投稿の防止
    header("Location: index.php?");
}

?>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Slot game</title>
</head>

<body>
    <div class="container">

        <?php require('header.php') ?>

        <?php if (isset($_SESSION['loginUser'])) : ?>
            <main class="d-flex justify-content-center">
                <div class="flex-column ">
                    <?php if (!empty($_SESSION['slot'])) : ?>
                        <table border="1" class="slot">
                            <tr>
                                <td><?= h($_SESSION['slot'][0][0]) ?></td>
                                <td><?= h($_SESSION['slot'][0][1]) ?></td>
                                <td><?= h($_SESSION['slot'][0][2]) ?></td>
                            </tr>
                            <tr>
                                <td><?= h($_SESSION['slot'][1][0]) ?></td>
                                <td><?= h($_SESSION['slot'][1][1]) ?></td>
                                <td><?= h($_SESSION['slot'][1][2]) ?></td>
                            </tr>
                            <tr>
                                <td><?= h($_SESSION['slot'][2][0]) ?></td>
                                <td><?= h($_SESSION['slot'][2][1]) ?></td>
                                <td><?= h($_SESSION['slot'][2][2]) ?></td>
                            </tr>
                        </table>
                    <?php endif; ?>

                    <form class="d-flex" action="" method="post">
                        <input class="form-control me-2" type="number" name="bet" placeholder="<?php echo isset($_SESSION['previousBet']) ? $_SESSION['previousBet'] : '' ?>" value="<?php echo isset($_SESSION['previousBet']) ? $_SESSION['previousBet'] : '' ?>">
                        <button class="btn btn-primary" type="submit">BET</button>
                        <input name="token" type="hidden" value="<?= h($_SESSION['token']) ?>">
                    </form>

                    <p><?= empty($_SESSION['message']) ? 'BETしてください' : h($_SESSION['message']) ?></p>
                </div>

            </main>
        <?php else : ?>
            <p>サインアップまたはログインしてください</p>
        <?php endif; ?>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</body>

</html>