<?php

class JWKResource {
    private string $kty = 'oct';
    private string $use = 'sig';
    private string $kid;
    private string $k;
    private string $alg = 'HS512';

    public function __construct(string $kid = '', string $k = '') {
        $this->kid = $kid;
        $this->k = $k;
    }

    public function getKty(): string {
        return $this->kty;
    }

    public function getUse(): string {
        return $this->use;
    }

    public function getKid(): string {
        return $this->kid;
    }

    public function getK(): string {
        return $this->k;
    }

    public function getAlg(): string {
        return $this->alg;
    }

    public function setKid(string $kid): void {
        $this->kid = $kid;
    }

    public function setK(string $k): void {
        $this->k = $k;
    }

    public function toArray(): array {
        return [
            'kty' => $this->kty,
            'use' => $this->use,
            'kid' => $this->kid,
            'k' => $this->k,
            'alg' => $this->alg
        ];
    }
}

?>