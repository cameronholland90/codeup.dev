<?php

$mysqli = new mysqli('127.0.0.1', 'codeup', 'password', 'codeup_mysqli_test_db');

// Check for errors
if ($mysqli->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
}

// // Create the query and assign to var
// $query = 'CREATE TABLE national_parks (
//     id INT NOT NULL AUTO_INCREMENT,
//     name VARCHAR(100) NOT NULL,
//     location VARCHAR(100) NOT NULL,
//     description TEXT NOT NULL,
//     date_established DATE NOT NULL,
//     area_in_acres FLOAT(10,2) NOT NULL,
//     PRIMARY KEY (id)
// );';

// // Run query, if there are errors then display them
// if (!$mysqli->query($query)) {
//     throw new Exception("Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error);
// }

$parks = [
['name' => 'Acadia', 'location' => 'Maine', 'date' => '1919-02-26', 'area_in_acres' => 47389.67, 'description' => "Covering most of Mount Desert Island and other coastal islands, Acadia features the tallest mountain on the Atlantic coast of the United States, granite peaks, ocean shoreline, woodlands, and lakes. There are freshwater, estuary, forest, and intertidal habitats."],

['name' => 'American Samoa', 'location' => 'American Samoa', 'date' => '1988-10-31', 'area_in_acres' => 9000.00, 'description' => "The southernmost national park is on three Samoan islands and protects coral reefs, rainforests, volcanic mountains, and white beaches. The area is also home to flying foxes, brown boobies, sea turtles, and 900 species of fish."],

['name' => 'Arches', 'location' => 'Utah', 'date' => '1971-11-12', 'area_in_acres' => 76518.98, 'description' => "This site features more than 2,000 natural sandstone arches, including the Delicate Arch. In a desert climate millions of years of erosion have led to these structures, and the arid ground has life-sustaining soil crust and potholes, natural water-collecting basins. Other geologic formations are stone columns, spires, fins, and towers."],

['name' => 'Badlands', 'location' => 'South Dakota', 'date' => '1978-11-10', 'area_in_acres' => 242755.94, 'description' => "The Badlands are a collection of buttes, pinnacles, spires, and grass prairies. It has the world's richest fossil beds from the Oligocene epoch, and there is wildlife including bison, bighorn sheep, black-footed ferrets, and swift foxes."],

['name' => 'Big Bend', 'location' => 'Texas', 'date' => '1944-06-12', 'area_in_acres' => 801163.21, 'description' => "Named for the Bend of the Rio Grande along the US–Mexico border, this park includes a part of the Chihuahuan Desert. A wide variety of Cretaceous and Tertiary fossils as well as cultural artifacts of Native Americans exist within its borders."],

['name' => 'Biscayne', 'location' => 'Florida', 'date' => '1980-06-28', 'area_in_acres' => 172924.07, 'description' => "Located in Biscayne Bay, this park at the north end of the Florida Keys has four interrelated marine ecosystems: mangrove forest, the Bay, the Keys, and coral reefs. Threatened animals include the West Indian Manatee, American crocodile, various sea turtles, and peregrine falcon."],

['name' => 'Black Canyon of the Gunnison', 'location' => 'Colorado', 'date' => '1999-10-21', 'area_in_acres' => 32950.03, 'description' => "The park protects a quarter of the Gunnison River, which has dark canyon walls from the Precambrian era. The canyon has very steep descents, and it is a site for river rafting and rock climbing. The narrow, steep canyon, made of gneiss and schist, is often in shadow, appearing black."],

['name' => 'Bryce Canyon', 'location' => 'Utah', 'date' => '1928-02-25', 'area_in_acres' => 35835.08, 'description' => "Bryce Canyon is a giant natural amphitheatre along the Paunsaugunt Plateau. The unique area has hundreds of tall sandstone hoodoos formed by erosion. The region was originally settled by Native Americans and later by Mormon pioneers."],

['name' => 'Canyonlands', 'location' => 'Utah', 'date' => '1964-09-12', 'area_in_acres' => 337597.83, 'description' => "This landscape was eroded into canyons, buttes, and mesas by the Colorado River, Green River, and their tributaries, which divide the park into three districts. There are rock pinnacles and other naturally sculpted rock, as well as artifacts from Ancient Pueblo Peoples."],

['name' => 'Capitol Reef', 'location' => 'Utah', 'date' => '1971-12-18', 'area_in_acres' => 241904.26, 'description' => "The park's Waterpocket Fold is a 100-mile (160 km) monocline that shows the Earth's geologic layers. Other natural features are monoliths and sandstone domes and cliffs shaped like the United States Capitol."],

['name' => 'Carlsbad Caverns', 'location' => 'New Mexico', 'date' => '1930-05-14', 'area_in_acres' => 46766.45, 'description' => "Carlsbad Caverns has 117 caves, the longest of which is over 120 miles (190 km) long. The Big Room is almost 4,000 feet (1,200 m) long, and the caves are home to over 400,000 Mexican Free-tailed Bats and sixteen other species. Above ground are the Chihuahuan Desert and Rattlesnake Springs."],

['name' => 'Channel Islands', 'location' => 'California', 'date' => '1980-05-05', 'area_in_acres' => 249561.00, 'description' => "Five of the eight Channel Islands are protected, and half of the park's area is underwater. The islands have a unique Mediterranean ecosystem. They are home to over 2,000 species of land plants and animals, and 145 are unique to them. The islands were originally settled by the Chumash people."],

['name' => 'Congaree', 'location' => 'South Carolina', 'date' => '2003-11-10', 'area_in_acres' => 26545.86, 'description' => "On the Congaree River, this park is the largest portion of old-growth floodplain forest left in North America. Some of the trees are the tallest in the Eastern US, and the Boardwalk Loop is an elevated walkway through the swamp."],

['name' => 'Crater Lake', 'location' => 'Oregon', 'date' => '1902-05-22', 'area_in_acres' => 183224.05, 'description' => "Crater Lake lies in the caldera of Mount Mazama formed 7,700 years ago after an eruption. It is the deepest lake in the United States and is known for its blue color and water clarity. There are two islands in the lake, and, with no inlets or outlets, all water comes through precipitation."],

['name' => 'Cuyahoga Valley', 'location' => 'Ohio', 'date' => '2000-10-11', 'area_in_acres' => 32860.73, 'description' => "This park along the Cuyahoga River has waterfalls, hills, trails, and displays about early rural living. The Ohio and Erie Canal Towpath Trail follows the Ohio and Erie Canal, where mules towed canal boats. The park has numerous historic homes, bridges, and structures.[21] The park also offers a scenic train ride with various trips available."],

['name' => 'Death Valley', 'location' => 'California, Nevada', 'date' => '1994-10-31', 'area_in_acres' => 3372401.96, 'description' => "Death Valley is the hottest, lowest, and driest place in the United States. Daytime temperatures have topped 130°F (54°C) and it is home to Badwater Basin, the lowest point in North America. There are canyons, colorful badlands, sand dunes, mountains, and over 1000 species of plants in this graben on a fault line. Further geologic points of interest are salt flats, springs, and buttes."],

['name' => 'Denali', 'location' => 'Alaska', 'date' => '1917-02-26', 'area_in_acres' => 4740911.72, 'description' => "Centered around the Mount McKinley, the tallest mountain in North America, Denali is serviced by a single road leading to Wonder Lake. McKinley and other peaks of the Alaska Range are covered with long glaciers and boreal forest. Wildlife includes grizzly bears, Dall sheep, caribou, and gray wolves."],

['name' => 'Dry Tortugas', 'location' => 'Florida', 'date' => '1992-10-26', 'area_in_acres' => 64701.22, 'description' => "The Dry Tortugas on the west end of the Florida Keys are the site of Fort Jefferson, the largest masonry structure in the Western Hemisphere. With most of the park being water, it is the home of coral reefs and shipwrecks and is only accessible by plane or boat."],

['name' => 'Everglades', 'location' => 'Florida', 'date' => '1934-05-30', 'area_in_acres' => 1508537.90, 'description' => "The Everglades are the largest subtropical wilderness in the United States. This mangrove ecosystem and marine estuary is home to 36 protected species, including the Florida panther, American crocodile, and West Indian manatee. Some areas have been drained and developed; restoration projects aim to restore the ecology."],

['name' => 'Gates of the Arctic', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 7523897.74, 'description' => "This northernmost park protects part of the Brooks Range and has no park facilities. The land is home to Alaska natives, who have relied on the land and caribou for 11,000 years."],

['name' => 'Glacier', 'location' => 'Montana', 'date' => '1910-05-11', 'area_in_acres' => 1013572.41, 'description' => "Part of Waterton Glacier International Peace Park, this park has 26 remaining glaciers and 130 named lakes under the tall Rocky Mountain peaks. There are historic hotels and a landmark road in this region of rapidly receding glaciers. These mountains, formed by an overthrust, have the world's best sedimentary fossils from the Proterozoic era."],

['name' => 'Glacier Bay', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 3224840.31, 'description' => "Glacier Bay has numerous tidewater glaciers, mountains, and fjords. The temperate rainforest and the bay are home to grizzly bears, mountain goats, whales, seals, and eagles. When discovered in 1794 by George Vancouver, the entire bay was covered by ice, but the glaciers have receded over 65 miles (105 km)."],

['name' => 'Grand Canyon', 'location' => 'Arizona', 'date' => '1919-02-26', 'area_in_acres' => 1217403.32, 'description' => "The Grand Canyon, carved out by the Colorado River, is 277 miles (446 km) long, up to 1 mile (1.6 km) deep, and up to 15 miles (24 km) wide. Millions of years of exposure has formed colorful layers of the Colorado Plateau in mesas and canyon walls."],

['name' => 'Grand Teton', 'location' => 'Wyoming', 'date' => '1929-02-26', 'area_in_acres' => 309994.66, 'description' => "Grand Teton is the tallest mountain in the Teton Range. The park's Jackson Hole valley and reflective piedmont lakes contrast with the tall mountains, which abruptly rise from the sage-covered valley."],

['name' => 'Great Basin', 'location' => 'Nevada', 'date' => '1986-10-27', 'area_in_acres' => 77180.00, 'description' => "Based around Wheeler Peak, the Great Basin has 5,000-year-old bristlecone pines, glacial moraines, and the limestone Lehman Caves. It has some of the country's darkest night skies, and there are animal species including Townsend's big-eared bat, Pronghorn, and Bonneville cutthroat trout."],

['name' => 'Great Sand Dunes', 'location' => 'Colorado', 'date' => '2004-09-13', 'area_in_acres' => 42983.74, 'description' => "The tallest dunes in North America are up to 750 feet (230 m) tall and neighbor grasslands, shrublands and wetlands. They were formed by sand deposits of the Rio Grande on the San Luis Valley. The park also has alpine lakes, six 13,000-foot mountains, and ancient forests."],

['name' => 'Great Smoky Mountains', 'location' => 'North Carolina, Tennessee', 'date' => '1934-06-15', 'area_in_acres' => 521490.13, 'description' => "The Great Smoky Mountains, part of the Appalachian Mountains, have a wide range of elevations, making them home to over 400 vertebrate species, 100 tree species, and 5000 plant species. Hiking is the park's main attraction, with over 800 miles (1,300 km) of trails, including 70 miles (110 km) of the Appalachian Trail. Other activities are fishing, horseback riding, and visiting some of nearly 80 historic structures."],

['name' => 'Guadalupe Mountains', 'location' => 'Texas', 'date' => '1966-10-15', 'area_in_acres' => 86415.97, 'description' => "This park has Guadalupe Peak, the highest point in Texas, the scenic McKittrick Canyon full of Bigtooth Maples, part of the Chihuahuan Desert, and a fossilized reef from the Permian."],

['name' => 'Haleakalā', 'location' => 'Hawaii', 'date' => '1916-08-01', 'area_in_acres' => 29093.67, 'description' => "The Haleakalā volcano on Maui has a very large crater with many cinder cones, Hosmer's Grove of alien trees, and the native Hawaiian Goose. The Kipahulu section has numerous pools with freshwater fish. This National Park has the greatest number of endangered species."],

['name' => 'Hawaii Volcanoes', 'location' => 'Hawaii', 'date' => '1916-08-01', 'area_in_acres' => 323431.38, 'description' => "This park on the Big Island protects the Kīlauea and Mauna Loa volcanoes, two of the world's most active. Diverse ecosystems of the park range from those at sea level to 13,000 feet (4,000 m)."],

['name' => 'Hot Springs', 'location' => 'Arkansas', 'date' => '1921-05-04', 'area_in_acres' => 5549.75, 'description' => "The only National Park in an urban area, this smallest National Park is based around the natural hot springs that have been managed for public use. Bathhouse Row preserves 47 of these with many beneficial minerals."],

['name' => 'Isle Royale', 'location' => 'Michigan', 'date' => '1931-05-03', 'area_in_acres' => 571790.11, 'description' => "The largest island in Lake Superior, this park is a site of isolation and wilderness. It has many shipwrecks, waterways, and hiking trails. The park also includes over 400 smaller islands in the waters up to 4.5 miles (7.2 km) from the island. There are only 20 mammal species and it is known for its wolf and moose relationship."],

['name' => 'Joshua Tree', 'location' => 'California', 'date' => '1994-10-31', 'area_in_acres' => 789745.47, 'description' => "Covering parts of the Colorado and Mojave Deserts and the Little San Bernardino Mountains, this is the home of the Joshua tree. Across great elevation changes are sand dunes, dry lakes, rugged mountains, and granite monoliths."],

['name' => 'Katmai', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 3674529.68, 'description' => "This park on the Alaska Peninsula protects the Valley of Ten Thousand Smokes, an ash flow formed by the 1912 eruption of Novarupta, as well as Mount Katmai. Over 2,000 grizzly bears come here to catch spawning salmon."],

['name' => 'Kenai Fjords', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 669982.99, 'description' => "Near Seward on the Kenai Peninsula, this park protects the Harding Icefield and at least 38 glaciers and fjords stemming from it. The only area accessible to the public by road is Exit Glacier, while the rest can only be viewed by boat tours."],

['name' => 'Kings Canyon', 'location' => 'California', 'date' => '1940-05-04', 'area_in_acres' => 461901.20, 'description' => "Home to several Giant sequoia groves and the General Grant Tree, the world's second largest, this park also has part of the Kings River, site of the granite Kings Canyon, and San Joaquin River, as well as the Boyden Cave."],

['name' => 'Kobuk Valley', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 1750716.50, 'description' => "Kobuk Valley has 61 miles (98 km) of the Kobuk River and three regions of sand dunes. Created by glaciers, the Great Kobuk, the Little Kobuk, and the Hunt River Sand Dunes can reach 100 feet (30 m) high and 100 °F (38 °C), and they are the largest dunes in the arctic. Twice a year, half a million caribou migrate through the dunes and across river bluffs that contain ice age fossils."],

['name' => 'Lake Clark', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 2619733.21, 'description' => "The region around Lake Clark has four active volcanoes, including Mount Redoubt, rivers, glaciers, and waterfalls. There are temperate rainforests, a tundra plateau, and three mountain ranges."],

['name' => 'Lassen Volcanic', 'location' => 'California', 'date' => '1916-08-09', 'area_in_acres' => 106372.36, 'description' => "Lassen Peak, the largest plug dome volcano in the world, is joined by all three other types of volcanoes in this park: shield, cinder dome, and composite. Other than the volcano, which last erupted in 1915, the park has hydrothermal areas, including fumaroles, boiling pools, and steaming ground, heated by molten rock under the peak."],

['name' => 'Mammoth Cave', 'location' => 'Kentucky', 'date' => '1941-07-01', 'area_in_acres' => 52830.19, 'description' => "With 392 miles (631 km) of passageways mapped, Mammoth Cave is by far the world's longest cave system. Cave animals include eight bat species, Kentucky cave shrimp, Northern cavefish, and cave salamanders. Above ground, the park contains Green River (Kentucky), 70 miles of hiking trails, sinkholes, and springs."],

['name' => 'Mesa Verde', 'location' => 'Colorado', 'date' => '1906-06-29', 'area_in_acres' => 52121.93, 'description' => "This area has over 4,000 archaeological sites of the Ancestral Pueblo, who lived here for 700 years. Cliff dwellings built in the 12th and 13th centuries include Cliff Palace, which has 150 rooms and 23 kivas, and the Balcony House, with passages and tunnels."],

['name' => 'Mount Rainier', 'location' => 'Washington', 'date' => '1899-05-02', 'area_in_acres' => 235625.00, 'description' => "Mount Rainier, an active volcano, is the most prominent peak in the Cascades, and it is covered by 26 named glaciers including Carbon Glacier and Emmons Glacier, the largest in the continental United States. The mountain is popular for climbing, and more than half of the park is covered by subalpine and alpine forests. Paradise on the south slope is one of the snowiest places in the world, and the Longmire visitor center is the start of the Wonderland Trail, which encircles the mountain."],

['name' => 'North Cascades', 'location' => 'Washington', 'date' => '1968-10-02', 'area_in_acres' => 504780.94, 'description' => "This complex includes the two units of the National Park and the Ross Lake and Lake Chelan National Recreation Areas. There are numerous glaciers, and popular hiking and climbing areas are Cascade Pass, Mount Shuksan, Mount Triumph, and Eldorado Peak."],

['name' => 'Olympic', 'location' => 'Washington', 'date' => '1938-06-29', 'area_in_acres' => 922650.86, 'description' => "Situated on the Olympic Peninsula, this park ranges from Pacific shoreline with tide pools to temperate rainforests to Mount Olympus. The glaciated Olympic Mountains overlook the Hoh Rain Forest and Quinault Rain Forest, the wettest area of the continental United States."],

['name' => 'Petrified Forest', 'location' => 'Arizona', 'date' => '1962-12-09', 'area_in_acres' => 93532.57, 'description' => "This portion of the Chinle Formation has a great concentration of 225-million-year-old petrified wood. The surrounding region, the Painted Desert, has eroded red-hued volcanic rock called bentonite. There are also dinosaur fossils and over 350 Native American sites."],

['name' => 'Pinnacles', 'location' => 'California', 'date' => '2013-01-10', 'area_in_acres' => 26605.73, 'description' => "Known for the namesake eroded leftovers of half of an extinct volcano, it is popular for its rock climbing."],

['name' => 'Redwood', 'location' => 'California', 'date' => '1968-10-02', 'area_in_acres' => 112512.05, 'description' => "This park and the co-managed state parks protect almost half of all remaining Coastal Redwoods, the tallest trees on Earth. There are three large river systems in this very seismically active area, and the 37 miles (60 km) of protected coastline have tide pools and seastacks. The prairie, estuary, coast, river, and forest ecosystems have varied animal and plant species."],

['name' => 'Rocky Mountain', 'location' => 'Colorado', 'date' => '1915-01-26', 'area_in_acres' => 265828.41, 'description' => "This section of the Rocky Mountains has ecosystems varying in elevation from the over 150 riparian lakes to Montane and subalpine forests to the alpine tundra. Large wildlife including mule deer, bighorn sheep, black bears, and cougars inhabit these igneous mountains and glacier valleys. The fourteener Longs Peak and Bear Lake are popular destinations."],

['name' => 'Saguaro', 'location' => 'Arizona', 'date' => '1994-10-14', 'area_in_acres' => 91439.71, 'description' => "Split into the separate Rincon Mountain and Tucson Mountain Districts, the dry Sonoran Desert is still home to much life in six biotic communities. Beyond the namesake Giant Saguaro cacti, there are barrel cacti, cholla cacti, and prickly pears, as well as Lesser Long-nosed Bats, Spotted Owls, and javelinas."],

['name' => 'Sequoia', 'location' => 'California', 'date' => '1890-09-25', 'area_in_acres' => 404051.17, 'description' => "This park protects the Giant Forest, which has the world's largest tree, General Sherman, as well as four of the next nine. It also has over 240 caves, the tallest mountain in the continental U.S., Mount Whitney, and the granite dome Moro Rock."],

['name' => 'Shenandoah', 'location' => 'Virginia', 'date' => '1926-05-22', 'area_in_acres' => 199045.23, 'description' => "Shenandoah's Blue Ridge Mountains are covered by hardwood forests that are home to tens of thousands of animals. The Skyline Drive and Appalachian Trail run the entire length of this narrow park that has more than 500 miles (800 km) of hiking trails along scenic overlooks and waterfalls of the Shenandoah River."],

['name' => 'Theodore Roosevelt', 'location' => 'North Dakota', 'date' => '1978-11-10', 'area_in_acres' => 70446.89, 'description' => "This region that enticed and influenced President Theodore Roosevelt is now a park of three units in the badlands. Besides Roosevelt's historic cabin, there are scenic drives and backcountry hiking opportunities. Wildlife includes American Bison, pronghorns, Bighorn sheep, and wild horses."],

['name' => 'Virgin Islands', 'location' => 'United States Virgin Islands', 'date' => '1956-08-02', 'area_in_acres' => 14688.87, 'description' => "The island of Saint John has rich human and natural history. There are Taino archaeological sites and ruins of sugar plantations from Columbus's time. Past the pristine beaches are mangroves, seagrass beds, coral reefs and algal plains."],

['name' => 'Voyageurs', 'location' => 'Minnesota', 'date' => '1971-01-08', 'area_in_acres' => 218200.17, 'description' => "This park on four main lakes, a site for canoeing, kayaking, and fishing, has a history of Ojibwe Native Americans, French fur traders called voyageurs, and a gold rush. Formed by glaciers, this region has tall bluffs, rock gardens, islands and bays, and historic buildings."],

['name' => 'Wind Cave', 'location' => 'South Dakota', 'date' => '1903-01-09', 'area_in_acres' => 28295.03, 'description' => "Wind Cave is distinctive for its calcite fin formations called boxwork and needle-like growths called frostwork. The cave, which was discovered by the sound of wind coming from a hole in the ground, is the world's densest cave system. Above ground is a mixed-grass prairie with animals such as bison, black-footed ferrets, and prairie dogs,[62] and Ponderosa pine forests home to cougars and elk."],

['name' => 'Wrangell –St. Elias', 'location' => 'Alaska', 'date' => '1980-12-02', 'area_in_acres' => 8323147.59, 'description' => "This mountainous land has the convergence of the Alaska, Chugach, and Wrangell-Saint Elias Ranges, which have many of the continent's tallest mountains over 16,000 feet (4,900 m), including Mount Saint Elias. More than 25% of this park of volcanic peaks is covered with glaciers, including the tidewater Hubbard Glacier, piedmont Malaspina Glacier, and valley Nabesna Glacier."],

['name' => 'Yellowstone', 'location' => 'Wyoming, Montana, Idaho', 'date' => '1872-05-01', 'area_in_acres' => 2219790.71, 'description' => "Situated on the Yellowstone Caldera, the first national park in the world has vast geothermal areas such as hot springs and geysers, the best-known being Old Faithful and Grand Prismatic Spring. The yellow-hued Grand Canyon of the Yellowstone River has numerous waterfalls, and four mountain ranges run through the park. There are almost 60 mammal species, including the gray wolf, grizzly bear, lynx, bison, and elk."],

['name' => 'Yosemite', 'location' => 'California', 'date' => '1890-10-01', 'area_in_acres' => 761266.19, 'description' => "Yosemite has towering cliffs, waterfalls, and sequoias in a diverse area of geology and hydrology. Half Dome and El Capitan rise from the central glacier-formed Yosemite Valley, as does Yosemite Falls, North America's tallest waterfall. Three Giant Sequoia groves and vast wilderness are home to diverse wildlife."],

['name' => 'Zion', 'location' => 'Utah', 'date' => '1919-11-19', 'area_in_acres' => 146597.60, 'description' => "This geologically unique area has colorful sandstone canyons, high plateaus, and rock towers. Natural arches and exposed formations of the Colorado Plateau make up a large wilderness of four ecosystems."]
];

// foreach ($parks as $park) {
// 	$escaped_desc = $mysqli->real_escape_string($park['description']);
//     $query = "INSERT INTO national_parks (name, location, date_established, area_in_acres, description)
//     		VALUES ('{$park['name']}', '{$park['location']}', '{$park['date']}', {$park['area_in_acres']}, '{$escaped_desc}');";
//     if (!$mysqli->query($query)) {
//     	throw new Exception('OH NOES! '. $mysqli->error);
//     }
// }

?>