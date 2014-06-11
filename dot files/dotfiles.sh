#!/bin/bash

for f in *.dot
do
    dot -Tpng $f -o ../images/${f/dot/png}
done
