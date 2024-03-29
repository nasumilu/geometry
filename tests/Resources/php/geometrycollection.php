<?php

/*
 * Copyright 2021 mlucas.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
return [
    'type' => 'geometrycollection',
    'geometries' => [
        require __DIR__ . '/point.php',
        require __DIR__ . '/linestring.php',
        require __DIR__ . '/polygon.php',
        require __DIR__ . '/multipoint.php',
        [
            'type' => 'geometrycollection',
            'geometries' => [
                require __DIR__ . '/point.php',
                require __DIR__ . '/linestring.php',
                require __DIR__ . '/polygon.php',
                require __DIR__ . '/multipoint.php'
            ]
        ]
    ]
];
