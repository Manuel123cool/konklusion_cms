#   Konklusion_cms: open source blog manager
#   Copyright (C) 2020  Manuel Maria Kümpel

#   This program is free software: you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation, either version 3 of the License, or
#   (at your option) any later version.

#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.

#   You should have received a copy of the GNU General Public License
#   along with this program.  If not, see <https://www.gnu.org/licenses/>.


#!/bin/bash

files=$(ls)

for file in $files
do
        if [ $file != 'update_git.sh' ]
        then
                sudo rm -r $file
        fi
done
base_path=$(pwd)

sudo git clone https://github.com/Manuel123cool/konklusion_cms.git

cd konklusion_cms
sudo mv * $base_path
cd $base_path

sudo rm README.md
sudo rm config.sh
sudo rm -r konklusion_cms

sudo chmod 777 *

cd php/data
sudo chmod 777  *
cd $base_path

cd php/
sudo chmod 777 mysqli.php
sudo chmod 777 data
sudo chmod 777 upload.php

sudo sed -i 's/#username/root/g' mysqli.php
sudo sed -i 's/#password//g'  mysqli.php
sudo sed -i 's/#name/example/g' upload.php
sudo sed -i 's/#password/example/g' upload.php
cd $base_path

sudo chmod 777 index.html
sudo sed -i 's/#replace/example/g' index.html
