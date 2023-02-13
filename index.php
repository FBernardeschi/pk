<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$sql = 'CREATE TABLE IF NOT EXISTS posts (
    post_id INTEGER PRIMARY KEY,
    post_type VARCHAR(50),
    post_type_mat VARCHAR(50),
    post_square VARCHAR(50),
    post_photo VARCHAR(50),
    post_price VARCHAR(11),
    mat_name_1 VARCHAR(100),
    mat_count_1 VARCHAR(15),
    mat_name_2 VARCHAR(100),
    mat_count_2 VARCHAR(15),
    mat_name_3 VARCHAR(100),
    mat_count_3 VARCHAR(15),
    mat_name_4 VARCHAR(100),
    mat_count_4 VARCHAR(15),
    mat_name_5 VARCHAR(100),
    mat_count_5 VARCHAR(15)
)';

$arrayType = array(
    '1'=>'Гостиная',
    '2'=>'Спальня',
    '3'=>'Кухня',
    '4'=>'Детская',
    '5'=>'Ванна',
    '6'=>'Прихожая'
);

$arrayTypeMat = array(
    '1'=>'Матовый',
    '2'=>'Глянцевый',
    '3'=>'Сатиновый',
    '4'=>'Тканевый'
);

$arrayTitle = array(
    '1'=>'Натяжной потолок в гостиной',
    '2'=>'Натяжной потолок в спальне',
    '3'=>'Натяжной потолок в кухне',
    '4'=>'Натяжной потолок в детской',
    '5'=>'Натяжной потолок в ванной',
    '6'=>'Натяжной потолок в прихожей'
);

$arrayText = array(
    '1'=>'Гостиная – место отдыха членов семьи или приема гостей. Поэтому хочется создать в комнате комфортную обстановку и впечатляющий вид. Удачное решение для украшения гостиной – натяжной потолок.',
    '2'=>'Спальня – место, где мы отдыхаем, поэтому важно ощущать там чувство комфорта. Многое зависит от того, как натяжной потолок в спальне соответствует ее интерьеру по цвету, фактуре, форме.'
);

$arraySolution = array(
    '1'=>'Готовые решения для гостиной:',
    '2'=>'Готовые решения для спальни:',
    '3'=>'Готовые решения для кухни:',
    '4'=>'Готовые решения для детской:',
    '5'=>'Готовые решения для прихожей:',
    '6'=>'Готовые решения для ванной:'
);

$db = new PDO('sqlite:post.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
# $q = $db->exec('DROP TABLE posts');
$q = $db->exec($sql);

require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->setBasePath('/potolok');

$app->get('/', function($request, $response, $args) use($twig, $db) {
    $q = $db->query('SELECT * FROM posts');
    $q->execute();
    $post = $q->fetchAll();

    $body = $twig->render('index.html', [
        'title'=>'Главная',
        'post'=>$post
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/promo', function($request, $response, $args) use($twig) {
    $body = $twig->render('promo.html', [
        'title'=>'Акции'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/admin', function($request, $response, $args) use($twig) {
    $body = $twig->render('admin.html', [
        'title'=>'Админ панель'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/admin', function($request, $response, $args) use($twig) {
    $data = $request->getParsedBody();

    $render = array('title'=>'Админ панель');
    if(isset($data['login']) && isset($data['password'])) {
        $render['enter'] = 'Вход выполнен!';
    };

    $body = $twig->render('admin.html', $render);

    $response->getBody()->write($body);
    return $response;
});

$app->get('/panel', function($request, $response, $args) use($twig, $db) {
    $q = $db->query('SELECT * FROM posts');
    $q->execute();
    $post = $q->fetchAll();

    $body = $twig->render('panel.html', [
        'title'=>'Админ панель',
        'post'=>$post
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/panel', function($request, $response, $args) use($twig, $db) {
    $data = $request->getParsedBody();

    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['file'];
    $directory = __DIR__ . '/upload';

    if($uploadedFile) {
        $filename = moveUploadedFile($directory, $uploadedFile);
    }

    global $arrayType, $arrayTypeMat;

    $type = $data['type'];
    $typeMat = $data['typeMat'];
    $square = $data['square'];
    $price = $data['price'];
    $mat_1 = $data['material-1'];
    $count_1 = $data['count-1'];
    $mat_2 = $data['material-2'];
    $count_2 = $data['count-2'];
    $mat_3 = $data['material-3'];
    $count_3 = $data['count-3'];
    $mat_4 = $data['material-4'];
    $count_4 = $data['count-4'];

    $sql = 'INSERT INTO posts(post_type, post_type_mat, post_square, post_photo, post_price, mat_name_1, mat_name_2, mat_count_1, mat_count_2) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        $arrayType[$type],
        $arrayType[$typeMat],
        $square,
        $filename,
        $price,
        $mat_1,
        $mat_2,
        $mat_3,
        $mat_4,
        $count_1,
        $count_2,
        $count_3,
        $count_4
    ));

    $body = $twig->render('panel.html', [
        'title'=>'Админ панель'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/ceiling', function($request, $response, $args) use($twig, $db) {
    $body = $twig->render('type-ceiling.html', [
        'title'=>'Виды потолков'
    ]);
    $response->getBody()->write($body);
    return $response;

});

$app->get('/ceiling-{id}', function($request, $response, $args) use($twig, $db) {
    global $arrayType, $arrayTitle, $arrayText, $arraySolution;

    $id = $args['id'];
    $typeCeiling = $arrayType[$id];

    $query = $db->prepare('SELECT * FROM posts WHERE post_type = ?');
    $query->execute(array($typeCeiling));
    $post = $query->fetchAll();

    $subTitle = $arrayTitle[$id];
    $text = $arrayText[$id];
    $solutionTitle = $arraySolution[$id];

    $body = $twig->render('ceiling.html', [
        'title'=>$typeCeiling,
        'post'=>$post,
        'text'=>$text,
        'subTitle'=>$subTitle,
        'solutionTitle'=>$solutionTitle
    ]);
    $response->getBody()->write($body);
    return $response;
});

function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    $filename = 'img_' . time() . '.jpg';

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->run();

?>