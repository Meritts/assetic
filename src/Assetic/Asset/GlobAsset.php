<?php

namespace Assetic\Asset;

/*
 * This file is part of the Assetic package.
 *
 * (c) Kris Wallsmith <kris.wallsmith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A collection of assets loaded by glob.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class GlobAsset extends AssetCollection
{
    /**
     * Constructor.
     *
     * @param string|array $globs   A single glob path or array of paths
     * @param string       $baseDir A base directory to use for determining each URL
     * @param array        $filters An array of filters
     *
     * @throws InvalidArgumentException If the base directory doesn't exist
     */
    public function __construct($globs, $baseDir = null, $filters = array())
    {
        if (null !== $baseDir && false === $baseDir = realpath($baseDir)) {
            throw new \InvalidArgumentException('Invalid base directory.');
        }

        $assets = array();
        foreach ((array) $globs as $glob) {
            if (false !== $paths = glob($glob)) {
                foreach (array_map('realpath', $paths) as $path) {
                    $assets[] = $asset = new FileAsset($path);

                    // determine url based on the base filesystem directory
                    if (null !== $baseDir && 0 === strpos($path, $baseDir)) {
                        $asset->setUrl(substr($path, strlen($baseDir) + 1));
                    }
                }
            }
        }

        parent::__construct($assets, $filters);
    }
}
