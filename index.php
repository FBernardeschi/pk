<?php

session_start(array('cookie_lifetime' => 86400,));


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$sql = 'CREATE TABLE IF NOT EXISTS posts (
    post_id INTEGER PRIMARY KEY,
    post_type VARCHAR(50),
    post_type_mat VARCHAR(50),
    post_type_teh VARCHAR(50),
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
    '6'=>'Прихожая',
    '7'=>'Лоджия',
    '8'=>'Офис'
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
    '6'=>'Натяжной потолок в прихожей',
    '7'=>'Натяжной потолок в лоджии',
    '8'=>'Натяжной потолок в офисе'
);

$arrayText = array(
    '1'=>'Гостиная – место отдыха членов семьи или приема гостей. Поэтому хочется создать в комнате комфортную обстановку и впечатляющий вид. Удачное решение для украшения гостиной – натяжной потолок.',
    '2'=>'Спальня – место, где мы отдыхаем, поэтому важно ощущать там чувство комфорта. Многое зависит от того, как натяжной потолок в спальне соответствует ее интерьеру по цвету, фактуре, форме.',
    '3'=>'Частый вопрос клиентов – можно ли установить натяжной потолок на кухне, не повлияют ли условия помещения на его внешний вид или изменения свойств. Ответ однозначный – можно, это наиболее современное и практичное решение.',
    '4'=>'Одно из преимуществ такой конструкции – возможность обустроить комнату для игр и отдыха ребенка благодаря разнообразию вариантов, их нестандартному решению, практичности.',
    '5'=>'Перед установкой натяжного потолка в ванной важно обратить внимание на микроклимат комнаты. Ведь для нее характерны, как температурные перепады, так и влажности. Этот момент смущает некоторых клиентов. Они считают, что натяжной потолок для ванной комнаты не подходит. На самом деле, это не так. Нужно просто учесть данный факт, сделать правильный выбор. ',
    '6'=>'Установкой натяжного потолка в прихожей легко устранить ее распространенный недостаток – тесноту и слабую освещенность. Помещение буквально преображается при создании зеркального эффекта или нежной матовой структуры в сочетании с оригинальными источниками света.',
    '7'=>'Простое и быстрое решение. Создаст уют в вашем офисе и скроет провода, короба, перекрытия плит и т.д.',
    '8'=>'Разнообразие фактур позволяет подобрать под любые помещения, даже не отапливаемые.'
);

$arrayTextMat = array(
    '1'=>'Матовая поверхность, как правило, полна мелких точек, заметных глазу только вблизи. Именно эти бугорки образуют лицевую сторону.',
    '2'=>'Находятся ли они сейчас на пике моды? Однозначно! Глянцевые натяжные потолки прочно укрепились в классике интерьера и до сих пор не сходят с модного Олимпа.',
    '3'=>'Именно спокойное отражение света — основная фишка натяжного сатинового потолка (белого особенно). При этом, в отличие от глянцевых вариантов, сатиновые могут отражать свет во всех направлениях, создавая приятное свечение. Также они могут изменять оттенок, это зависит от освещения в комнате.',
    '4'=>'Тканевые натяжные потолки — один из самых проверенных вариантов декора. Они известны всем и прошли хорошую проверку временем. При этом тканевое полотно для натяжных потолков отличается материалом, фактурой, формой и другими характеристиками.'
);

$arraySolution = array(
    '1'=>'Готовые решения для гостиной:',
    '2'=>'Готовые решения для спальни:',
    '3'=>'Готовые решения для кухни:',
    '4'=>'Готовые решения для детской:',
    '5'=>'Готовые решения для прихожей:',
    '6'=>'Готовые решения для ванной:',
    '7'=>'Готовые решения для лоджии:',
    '8'=>'Готовые решения для офиса:'
);

$arrayTehnology = array(
    ['1', 'noshow.jpg', 'Бесшовные', '300', 'Бесшовные натяжные потолки имеют вид обычного, но идеально ровного потолка после побелки. Они характеризуются довольно широкой сферой применения. Их обустраивают в бытовых помещениях, а также в комплексах спортивной и медицинской специализации.'],
    ['2', 'karniz.jpg', 'Ниша под карниз', '350', 'Натяжные потолки с нишей под карниз имеют несколько преимуществ по сравнению с традиционным расположением от стены до стены. Стоимость натяжного потолка с работой по устройству ниши для штор увеличится не значительно, но дополнительные расходы окупятся применением более низкого по цене карниза для штор, отсутствием необходимости ламбрекенов и вероятных расходов по демонтажу полотна в случае замены стояковых труб.'],
    ['3', 'noline.jpg', 'Криволинейные', '490', 'В природе нет ни одной идеально ровной линии, даже стволы деревьев и травинки не симметричны и плавно изогнуты. Это одна из причин, почему так приятно отдыхать вне цивилизации. Там, где появляется человек, - ровные линии столбов и проводов, домов и улиц, мебели и техники. Наше зрение устает от этих квадратно-прямоугольных рамок и ищет плавные линии для отдыха. Вот почему в последнее время все популярнее среди клиентов становятся криволинейные натяжные потолки. Под этим названием объединяются очень разные потолочные системы: одноуровневые потолки двух или нескольких цветов и фактур, соединенных криволинейно, многоуровневые своды, арки и купола, а также 3D потолки с конусами и другими объемными фигурами.'],
    ['4', 'twolevel.jpg', 'Двухуровневые', '590', 'Двухуровневый потолок — модное и эффектное интерьерное решение, которое помогает скрывать неровности потолочных перекрытий, создавать оригинальное освещение, осуществлять зонирование пространства. Проще всего идею реализовать с помощью натяжных тканевых или пленочных полотен. Комбинируя разные фактуры, цвета и формы полотна, можно не только решить перечисленные выше задачи, но и оптически увеличить помещение.'],
    ['5', 'kombo.jpg', 'Комбинированные', '640', 'Комбинированный натяжной потолок – удивительное сочетание  глянцевых и матовых полотен различных цветов, неповторимые многоуровневые конструкции на основе гипсокартона и профилей. Произвольный полет фантазии и совершенные технологии, современные материалы различной фактуры и широкая цветовая палитра составляют основу для реализации креативных дизайнерских решений при создании комбинированных потолков. Каждый изгиб и линия потолочной конструкции комбинированного типа – настоящее проявление совершенного вкуса и любви к дорогим и качественным вещам.'],
    ['6', 'cloud.jpg', 'Парящие', '800', 'Парящий потолок - это натяжной потолок, смонтированный особым способом. При помощи специального профиля, он монтируется с отступом от стен по всему периметру. Периметр закрывает прозрачная лента- рассеиватель, под которой монтируется светодиодная лента.'],
    ['7', 'diod.jpg', 'С подсветкой', '850', 'Светопрозрачный натяжной потолок – двоюродный брат обычного натяжного. Смысл почти одинаковый. Металлический или пластиковый каркас по периметру помещения и натянутое на него ПВХ или тканевое полотно. Вот правда полотно это не простое, а пропускающее свет. И это только половина секрета. Главный фокус светопрозрачного потолка – это спрятанная за полотном подсветка. Выключена – перед тобой просто обычный натяжной потолок, включена – комнату заливает магия. Иллюзии окна, аквариума, стеклянной крыши или просто исполина-светильника заставляют неискушенных гостей разевать рты и замирать в благоговейном трепете.'],
    ['8', 'photo.jpg', 'Фотопечать', '900', 'Натяжные потолки с фотопечатью становятся все популярнее в последнее время. Преимущества очевидны – быстрый монтаж без пыли и грязи, водостойкость и долговечность, а возможности фотопечати позволяют воплотить любые мечты, подобрав подходящий потолок к любому интерьеру.'],
    ['9', 'diodline.jpg', 'Световые линии', '1200', 'Световые линии на натяжном потолке – относительно новый вид дизайна потолка. Суть такого дизайна заключается в том, что на потолок устанавливают специальный профиль для светодиодной ленты. Благодаря led-ленте и равномерному расположению линий, свет заполняет все помещение. Вы получаете полноценное основное освещение. А внешний вид потолка впечатляет, помещение выглядит стильно и эффектно'],
    ['10', 'kraab.jpg', 'KRAAB 3.0', '1450', 'Одной из новейших систем для крепления натяжного потолка является – KRAAB. Без этих изделий современный ремонт сложно представить. При помощи профилей крааб потолок будет плотно прилегать к стене, что позволит не выбиваться за рамки дизайна и создавать новые решения. Крааб 3.0 это система, которая позволяет примыкать потолку к стене плотно без щелей. Крепеж состоит из шнура ПВХ и металлического профиля. Работа получается безупречной, если стены ровные. Главное достоинство такого крепежа в том, что примыкание потолка к стене идеальное и не потребуется дополнительной отделки.'],
    ['11', 'apply.jpg', 'Резные потолки Apply', '1900', 'Apply — это натяжные потолки нового поколения с декоративными вырезами, которые могут быть в виде отдельных фигур и узоров либо распространяться на всю площадь полотна. Такая отделка в любом помещении выглядит выразительно за счет необычного оформления, а также игры света, оттенков и фактуры. Рассмотрим подробно, что представляют собой технология резных потолков Apply, какие возможны варианты рисунков и способы освещения.'],
    ['12', 'starscloud.jpg', 'Звездное небо', '2500', 'Потолок «Звездное небо» – это сочетание натяжного полотна, светового оборудования и программы, которая управляет эффектами. Для реализации этой идеи разработаны различные виды проекторов, ламповые и светодиодные, несколько методов монтажа, а также разнообразные дополнительные элементы: хрусталики, светильники, декоративные насадки.'],
);

$lamps = array(
    array('Встраиваемый светильник FERON светодиод 18W, 1120Lm, 4000K , белый, AL2110', '51236678.jpg', '900'),
    array('Светильник FERON ИВО-50w 12в, G5.3, хром 15113', '55956830.jpg', '200'),
    array('Встраиваемый потолочный светильник FERON MR16 G5.3, хром DL2811', '52391454.jpg', '500'),
    array('Точечный светильник Светкомплект E14х40Вт белый R50.W', '51267902.jpg', '200'),
    array('Светодиодный встраиваемый поворотный светильник REXANT Bagel 12 Вт 4000 К LED 613-002', '64435135.jpg', '250')
);

$promo = array(
    array('Именинникам скидка 20% !', 'Скидка распространяется в интервале 14 дней до и после Дня Рождения.', 'gift.jpg'),
    array('Новоселам и пенсионерам скидка 10 % !', '', 'pens.jpg'),
    array('Каждый второй и последующие потолки со скидкой 10%', 'Скидка распространяется при повторном заказе в течении одного года', 'twice.jpg')
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
    

    $q = $db->query('SELECT * FROM posts ORDER BY post_id DESC LIMIT 10');
    $q->execute();
    $post = $q->fetchAll();

    $body = $twig->render('index.html', [
        'title'=>'Главная',
        'post'=>$post,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/promo', function($request, $response, $args) use($twig) {
    global $promo;

    $body = $twig->render('promo.html', [
        'title'=>'Акции',
        'promo'=>$promo,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function($request, $response, $args) use($twig) {

    $body = $twig->render('about.html', [
        'title'=>'О нас',
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/admin', function($request, $response, $args) use($twig) {
    if(isset($_SESSION['logged'])) {
        if($_SESSION['logged'] == true) {
            return $response
            ->withHeader('Location', '/potolok/panel')
            ->withStatus(302);
        };
    };    

    $body = $twig->render('admin.html', [
        'title'=>'Админ панель',
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/admin', function($request, $response, $args) use($twig, $app) {
    $data = $request->getParsedBody();

    $render = array('title'=>'Админ панель');
    if((isset($data['login']) && isset($data['password'])) && ($data['login'] === '190gena190' && $data['password'] === '190gena190')) {
        $_SESSION['logged'] = true;
        return $response
        ->withHeader('Location', '/potolok/panel')
        ->withStatus(302);
    } else {
        $render['enter'] = 'Вход выполнен!';
    };

    $body = $twig->render('admin.html', $render);

    $response->getBody()->write($body);
    return $response;
});

$app->get('/panel', function($request, $response, $args) use($twig, $db) {
    if(!$_SESSION['logged']) {
        return $response
        ->withHeader('Location', '/potolok/admin')
        ->withStatus(302);
    };
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
    if(!$_SESSION['logged']) {
        return $response
        ->withHeader('Location', '/potolok/admin')
        ->withStatus(302);
    };

    $data = $request->getParsedBody();

    if(isset($data['panel_post'])){
        $id = $data['id'];
        $sql = 'DELETE FROM posts WHERE post_id = ' . $id;
        $stmt = $db->query($sql);
        $stmt->execute();

        $q = $db->query('SELECT * FROM posts');
        $q->execute();
        $post = $q->fetchAll();

        $body = $twig->render('panel-content.html', [
            'post'=>$post
        ]);
        $response->getBody()->write($body);
        return $response;
    }

    $uploadedFiles = $request->getUploadedFiles();
    $uploadedFile = $uploadedFiles['file'];
    $directory = __DIR__ . '/upload';

    if($uploadedFile) {
        $filename = moveUploadedFile($directory, $uploadedFile);
    }

    global $arrayType, $arrayTypeMat, $arrayTehnology;

    $type = $data['type'];
    $typeMat = $data['typeMat'];
    $typeTeh = $data['typeTeh'];
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

    $sql = 'INSERT INTO posts(post_type, post_type_mat, post_type_teh, post_square, post_photo, post_price, mat_name_1, mat_name_2, mat_name_3, mat_name_4, mat_count_1, mat_count_2, mat_count_3, mat_count_4) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        $arrayType[$type],
        $arrayTypeMat[$typeMat],
        $arrayTehnology[$typeTeh-1][2],
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
    global $arrayTehnology;
    $body = $twig->render('type-ceiling.html', [
        'title'=>'Виды потолков',
        'arrayTehnology'=>$arrayTehnology,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;

});

$app->get('/ceiling-{id}', function($request, $response, $args) use($twig, $db) {
    global $arrayType, $arrayTitle, $arrayText, $arraySolution;

    $id = $args['id'];

    if($id > 8) {
        $body = $twig->render('404.html', [
            'title'=>'404 not found',
            'url'=>getUrl($request)
        ]);
        $response->getBody()->write($body);
        return $response;
    };

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
        'solutionTitle'=>$solutionTitle,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/type-{id}', function($request, $response, $args) use($twig, $db) {
    global $arrayTypeMat, $arrayTextMat;
    $id = $args['id'];

    if($id > 4) {
        $body = $twig->render('404.html', [
            'title'=>'404 not found',
            'url'=>getUrl($request),
            'url'=>getUrl($request)
        ]);
        $response->getBody()->write($body);
        return $response;
    };

    $typeCeiling = $arrayTypeMat[$id];
    $text = $arrayTextMat[$id];
    $subTitle = $typeCeiling . ' натяжной потолок';

    $query = $db->prepare('SELECT * FROM posts WHERE post_type_mat = ?');
    $query->execute(array($typeCeiling));
    $post = $query->fetchAll();

    $body = $twig->render('type.html', [
        'title'=>$typeCeiling .' потолок',
        'post'=>$post,
        'text'=>$text,
        'subTitle'=>$subTitle,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;

});

$app->get('/tehnology-{id}', function($request, $response, $args) use($twig, $db) {
    global $arrayTehnology;
    $id = $args['id'];

    if($id > 12) {
        $body = $twig->render('404.html', [
            'title'=>'404 not found',
            'url'=>getUrl($request)
        ]);
        $response->getBody()->write($body);
        return $response;
    };

    $typeTehnology = $arrayTehnology[$id-1][2];
    $text = $arrayTehnology[$id-1][4];
    $price = $arrayTehnology[$id-1][3];

    $query = $db->prepare('SELECT * FROM posts WHERE post_type_teh = ?');
    $query->execute(array($typeTehnology));
    $post = $query->fetchAll();

    $body = $twig->render('tehnology.html', [
        'title'=>$typeTehnology,
        'post'=>$post,
        'text'=>$text,
        'subTitle'=>$typeTehnology,
        'price'=>$price,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;

});

$app->get('/price', function($request, $response, $args) use ($twig) {
    $body = $twig->render('price.html', [
        'title'=>'Цены',
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/turnkey-solutions', function($request, $response, $args) use ($twig, $db) {
    $sql = 'SELECT * FROM posts ORDER BY post_id DESC LIMIT 10';
    $post = $db->query($sql);

    $body = $twig->render('turnkey-solutions.html', [
        'title'=>'Готовые решения',
        'post'=> $post,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/turnkey-solutions', function($request, $response, $args) use ($twig, $db) {
    $data = $request->getParsedBody();
    $tehnology = $data['tehnology'];
    $solution = $data['solution'];

    if($tehnology && $solution) {
        $sql = 'SELECT * FROM posts WHERE post_type = ? AND post_type_mat = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$tehnology, $solution]);
    } elseif(!$tehnology && !$solution) {
        $sql = 'SELECT * FROM posts LIMIT 10';
        $stmt = $db->query($sql);
    } else {
        // $sql = $tehnology ? 'SELECT * FROM posts WHERE post_type = '.$tehnology : 'SELECT * FROM posts WHERE post_type_mat = '.$solution;
        $sql = $tehnology ? 'SELECT * FROM posts WHERE post_type = "' . $tehnology . '"' : 'SELECT * FROM posts WHERE post_type_mat = "' . $solution . '"';
        $stmt = $db->query($sql);
    }
    # $sql = 'SELECT * FROM posts WHERE post_type = ?';
    
    $post = $stmt->fetchAll();

    $body = $twig->render('turnkey-solutions-content.html', [
        'title'=>'Готовые решения',
        'post'=>$post,
        'url'=>getUrl($request)
    ]);

    $response->getBody()->write($body);
    return $response;
});

$app->get('/lamp', function($request, $response, $args) use ($twig) {
    global $lamps;

    $body = $twig->render('lamp.html', [
        'title'=>'Светильники',
        'lamps'=>$lamps,
        'url'=>getUrl($request)
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/sender', function($request, $response, $args) use ($twig) {
    // Token TG
    $token = "6066749292:AAGjzsVyKC9CQKyJ4GPAwNY-1MagRbUWFs0";

    $data = $request->getParsedBody();

    $getQuery = array(
        "chat_id" 	=> 350961333,
        "text"  	=> "Заявка перезвонить от " . $data['name'] . "\nТелефон: " . $data['phone'],
        "parse_mode" => "html"
    );
    $ch = curl_init("https://api.telegram.org/bot". $token ."/sendMessage?" . http_build_query($getQuery));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
    
    return $response;
});

// 404 not found

$app->get('/{slug}', function($request, $response, $args) use ($twig) {
    $body = $twig->render('404.html', [
        'title'=>'404 not found'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->post('/{slug}', function($request, $response, $args) use ($twig) {
    $body = $twig->render('404.html', [
        'title'=>$args['slug']
    ]);
    $response->getBody()->write($body);
    return $response;
});

function getUrl($request) {
    $routeContext = RouteContext::fromRequest($request);
    return $request->getUri()->getPath();
};

function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    $filename = 'img_' . time() . '.jpg';

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

$app->run();

?>