<?php
addComponent('header', [
        'title' => 'Home',
        'description' => 'This is home page'
]);
?>

<body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">

<?php
addComponent('navbar');
?>

<?php
include 'views/'.$view.'.php';
?>

</body>

</html>
