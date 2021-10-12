<?php
$firstContent = $_GET['first'];
$secondContent = $_GET['second'];

$first = explode('.', $firstContent);
$second = explode('.', $secondContent);

if ($first[count($first) - 2] < $second[count($second) - 2]) {
    $multiply = $second[count($second) - 2] - $first[count($first) - 2];
    $result = ($second[count($second) - 1] + ($multiply * 255)) - $first[count($first) - 1];
}
if ($first[count($first) - 2] > $second[count($second) - 2]) {
    $multiply = $first[count($first) - 2] - $second[count($second) - 2];
    $result = ($first[count($first) - 1] + ($multiply * 255)) - $second[count($second) - 1];
}
if ($first[count($first) - 2] === $second[count($second) - 2]) {
    $result = $second[count($second) - 1] - $first[count($first) - 1];
}

$resultContents = [];
$selector = $first[count($first) - 1];

$resultContents[] = $firstContent;
$resultContents[] = $secondContent;

$address = $first;
unset($address[count($address) - 1]);
unset($address[count($address) - 1]);

for ($i = $selector; $i < ($selector + $result); $i++) {
    $link = implode('.', $address);
    $count = $first[count($first) - 1] + $i;
    $secCount = intdiv($count, 255);

    while ($count > 254) {
        $count = $count - 254;
    }

    $resultContents[] = $link . '.' . $secCount + 1 . '.' . $count;
}

foreach ($resultContents as $resultContent) {
    echo '<br>';
    echo $resultContent;
}

