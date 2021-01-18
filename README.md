# Dependency control
Way to explicit complexity control

## Goals
This tool should 
 - force explicit assignment of _every_ class or interface to some layer
 - forbid any dependency between layers that not explicitly allowed

## Report structure
 - summary
 - unknown elements: Unknown elements
   (we don't include `UnknownDependsOn*` in this list)
 - violations: Forbidden, Depends on unknown
   (but we include DependsOnUnknown here)
```
- from item / layer
    - to item / layer
```

 - allowed dependencies: N

## Possible checker results
 - allowed / forbidden dependencies
  - from item / layer
    - to item / layer
   
 - unknown dependencies (from item without layers)
  - from item
    - to item / layer

 - unknown dependencies (to item without layers)
  - from item
    - to item / layer

- unknown dependencies (no layers)
    - from item
        - to item

 - unknown item without dependencies
    - item


## How it should operate

According to report format, looks like we don't need to perform unknownElements dependency check at all.  
Instead, we should put it to separate report first. - But, it works now, and covered. So let ot be.

## Questions  

One more thing I want to implement is IO checker, and corresponding markup of a layers.
Only explicit IO layer may perform IO, and only IO layer may depends on it. Also, it should depend on some interface.  
So, both pure and IO layers depends on some interface.

