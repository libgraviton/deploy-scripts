# graviton/deploy-scripts

These are currently mia.

I intend this to contain the low level parts of all the ``deploy.sh`` and ``cf-multipush.sh`` things
we have been writing. It should only depend on ``symfony/console`` and ``symfony/process`` and have
heaps of test coverage.

I intend for this to support both cf and docker cases. When it's done we'll wrap it up in a simple
``GravitonDeployBundle`` and the grey beards of early will look upon our continuous deploy rig with
awe and wonder whilst glancing at the door nervously.
