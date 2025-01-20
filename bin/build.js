import * as esbuild from 'esbuild'

esbuild.build({
    entryPoints: ['./resources/js/nouislider.js'],
    outfile: './dist/nouislider.js',
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    treeShaking: true,
    target: ['es2020'],
    minify: true,
})
