<?php

namespace DockerList;

use Docker\API\Model\ContainerSummary;
use Docker\Docker;

class Controller
{
    public function __construct(
        private Docker $client,
        private string $hostname = 'localhost',
        private ?int $excludePort = null
    ) {
    }

    /**
     * Gets link for each container with public ports, grouped by container name.
     *
     * @return array<string,string[]>
     */
    public function getLinks(): array
    {
        $containers = $this->client->containerList();
        if (!is_array($containers)) {
            return [];
        }

        $namesAndAddresses = [];

        foreach ($containers as $container) {
            foreach ($this->getContainerPublicPorts($container) as $port) {
                $namesAndAddresses[$this->getContainerName($container)][] = 'http://' . $this->hostname . ':' . $port;
            }
        }

        return $namesAndAddresses;
    }

    /**
     * Gets the public ports for a container.
     *
     * @param ContainerSummary $container
     * @return int[]
     */
    protected function getContainerPublicPorts(ContainerSummary $container): array
    {
        $ports = array_map(function ($port) {
            return intval($port->getPublicPort());
        }, $container->getPorts() ?? []);

        $ports = array_unique($ports);

        $ports = array_filter(
            array_unique($ports),
            function ($port) {
                if ($port <= 0 || $port > 65535) {
                    return false;
                }

                // Exclude the port if it matches the request port, to avoid linking to itself.
                if ($this->excludePort !== null && $this->excludePort === $port) {
                    return false;
                }

                return true;
            }
        );
        asort($ports);

        return $ports;
    }

    protected function getContainerName(ContainerSummary $container): string
    {
        return ltrim(implode(', ', $container->getNames() ?? []), '/');
    }
}
