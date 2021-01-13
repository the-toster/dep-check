# Dependency control
Way to explicit complexity control

## Goals
This tool should 
 - force explicit assignment of _every_ class or interface to some layer
 - forbid any dependency between layers that not explicitly allowed

## Report structure
 - allowed / forbidden dependencies
  - from item
    - to item
      - from layer
       - to layer
   
 - unknown dependencies (from item without layers)
  - from item
    - to item
        - to layer

 - unknown dependencies (to item without layers)
  - from item
    - to item
        - from layer

- unknown dependencies (no layers)
    - from item
        - to item

 - unknown item without dependencies
    - item
