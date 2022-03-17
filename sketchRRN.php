<?php
$model = "cat";
if (isset($_GET["model"])) { $model = $_GET['model']; }

$models = ['alarm_clock','ambulance','angel','ant','antyoga','backpack','barn','basket','bear','bee','beeflower','bicycle','bird','book','brain','bridge','bulldozer','bus','butterfly','cactus','calendar','castle','cat','catbus','catpig','chair','couch','crab','crabchair','crabrabbitfacepig','cruise_ship','diving_board','dog','dogbunny','dolphin','duck','elephant','elephantpig','eye','face','fan','fire_hydrant','firetruck','flamingo','flower','floweryoga','frog','frogsofa','garden','hand','hedgeberry','hedgehog','helicopter','kangaroo','key','lantern','lighthouse','lion','lionsheep','lobster','map','mermaid','monapassport','monkey','mosquito','octopus','owl','paintbrush','palm_tree','parrot','passport','peas','penguin','pig','pigsheep','pineapple','pool','postcard','power_outlet','rabbit','rabbitturtle','radio','radioface','rain','rhinoceros','rifle','roller_coaster','sandwich','scorpion','sea_turtle','sheep','skull','snail','snowflake','speedboat','spider','squirrel','steak','stove','strawberry','swan','swing_set','the_mona_lisa','tiger','toothbrush','toothpaste','tractor','trombone','truck','whale','windmill','yoga','yogabicycle','everything'];
?>

<!--  
Available models

  'alarm_clock',    'crab',                 'key',            'radio',            'truck',
  'ambulance',      'crabchair',            'lantern',        'radioface',        'whale',
  'angel',          'crabrabbitfacepig',    'lighthouse',     'rain',             'windmill',
  'ant',            'cruise_ship',          'lion',           'rhinoceros',       'yoga',
  'antyoga',        'diving_board',         'lionsheep',      'rifle',            'yogabicycle',
  'backpack',       'dog',                  'lobster',        'roller_coaster',   'everything',
  'barn',           'dogbunny',             'map',            'sandwich',
  'basket',         'dolphin',              'mermaid',        'scorpion',
  'bear',           'duck',                 'monapassport',   'sea_turtle',
  'bee',            'elephant',             'monkey',         'sheep',
  'beeflower',      'elephantpig',          'mosquito',       'skull',
  'bicycle',        'eye',                  'octopus',        'snail',
  'bird',           'face',                 'owl',            'snowflake',
  'book',           'fan',                  'paintbrush',     'speedboat',
  'brain',          'fire_hydrant',         'palm_tree',      'spider',
  'bridge',         'firetruck',            'parrot',         'squirrel',
  'bulldozer',      'flamingo',             'passport',       'steak',
  'bus',            'flower',               'peas',           'stove',
  'butterfly',      'floweryoga',           'penguin',        'strawberry',
  'cactus',         'frog',                 'pig',            'swan',
  'calendar',       'frogsofa',             'pigsheep',       'swing_set',
  'castle',         'garden',               'pineapple',      'the_mona_lisa',
  'cat',            'hand',                 'pool',           'tiger',
  'catbus',         'hedgeberry',           'postcard',       'toothbrush',
  'catpig',         'hedgehog',             'power_outlet',   'toothpaste',
  'chair',          'helicopter',           'rabbit',         'tractor',
  'couch',          'kangaroo',             'rabbitturtle',   'trombone',

-->



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Getting Started with ml5.js</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.2.0/p5.min.js"></script>
  <script src="https://unpkg.com/ml5@latest/dist/ml5.min.js"></script>
</head>

<body>
<?php foreach ($models as $m): ?>
  <a href="?model=<?= $m ?>"><?= $m ?></a>
<?php endforeach ?>
<p id="status"></p>
<button id="clear">Clear</button>
<script>

// Copyright (c) 2019 ml5
//
// This software is released under the MIT License.
// https://opensource.org/licenses/MIT

/* ===
ml5 Example
SketchRNN
=== */

var modelName = "<?= $model ?>";

// The SketchRNN model
let model;
// Start by drawing
let previous_pen = 'down';
// Current location of drawing
let x, y;
// The current "stroke" of the drawing
let strokePath;
let seedStrokes = [];

// Storing a reference to the canvas
let canvas;

function setup() {
  canvas = createCanvas(640, 480);
  // Hide the canvas until the model is ready
  canvas.hide();

  background(220);
  // Load the model
  // See a list of all supported models: https://github.com/ml5js/ml5-library/blob/master/src/SketchRNN/models.js
  model = ml5.sketchRNN(modelName, modelReady);

  // Button to start drawing
  let button = select('#clear');
  button.mousePressed(clearDrawing);
}

// The model is ready
function modelReady() {
  canvas.show();
  // sketchRNN will begin when the mouse is released
  canvas.mouseReleased(startSketchRNN);
  select('#status').html('model ready ('+ modelName +') - sketchRNN will begin after you draw with the mouse');
}

// Reset the drawing
function clearDrawing() {
  background(220);
  // clear seed strokes
  seedStrokes = [];
  // Reset model
  model.reset();
}

// sketchRNN takes over
function startSketchRNN() {
  // Start where the mouse left off
  x = mouseX;
  y = mouseY;
  // Generate with the seedStrokes
  model.generate(seedStrokes, gotStroke);
}

function draw() {
  // If the mosue is pressed capture the user strokes 
  if (mouseIsPressed) {
    // Draw line
    stroke(100);
    strokeWeight(3.0);
    line(pmouseX, pmouseY, mouseX, mouseY);
    // Create a "stroke path" with dx, dy, and pen
    let userStroke = {
      dx: mouseX - pmouseX,
      dy: mouseY - pmouseY,
      pen: 'down'
    };
    // Add to the array
    seedStrokes.push(userStroke);
  }

  // If something new to draw
  if (strokePath) {
    // If the pen is down, draw a line
    if (previous_pen == 'down') {
      stroke(0);
      strokeWeight(3.0);
      line(x, y, x + strokePath.dx, y + strokePath.dy);
    }
    // Move the pen
    x += strokePath.dx;
    y += strokePath.dy;
    // The pen state actually refers to the next stroke
    previous_pen = strokePath.pen;

    // If the drawing is complete
    if (strokePath.pen !== 'end') {
      strokePath = null;
      model.generate(gotStroke);
    }
  }
}

// A new stroke path
function gotStroke(err, s) {
  strokePath = s;
}  
</script>

</body>
</html>
