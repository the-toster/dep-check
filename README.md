# Dependency control
Way to explicit complexity control

## Goals
This tool should 
 - force explicit assignment of _every_ class or interface to some layer
 - forbid any dependency between layers that not explicitly allowed
 - force to markup IO layers
 - disallow direct dependencies from pure to IO

## Report structure
we need some summarizer to access report records
 - summary (number of allowed deps, violations, unknown elements)
 - unknown elements: Unknown elements  
   (we don't include `UnknownDependsOn*` in this list)
   - just list of elements
 - violations: Forbidden, Depends on unknown  
   (but we include DependsOnUnknown here)
```
   - from item / layer
    - to item [/ layer] : position
```

## Possible checker results
 - allowed / forbidden dependencies
  - from item / layer
    - to item / layer
   
 - unknown dependencies (from item without layers)
  - from item
    - to item / layer

 - unknown dependencies (to item without layers)
  - from item / layer
    - to item

- unknown dependencies (no layers)
    - from item
        - to item

 - unknown item without dependencies
    - item


## How it should operate
Somehow get all definitions from source files - populate `Nodes` (and dependencies).
Dependencies should contain related node id (maybe id should contain a type?), type of dependency, and `Position`
Than we translate it into an elements set - by assigning layers. Maybe just nodes with layers?
