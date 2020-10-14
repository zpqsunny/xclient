<?php

namespace Http;

class XClient
{
    private $curl;

    private $url = '';

    private $headers = [];

    private $params = [];

    private $data = '';

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function setAutoReferer(bool $autoReferer)
    {
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, $autoReferer);
        return $this;
    }

    public function setReturnTransfer(bool $returnTransfer)
    {
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $returnTransfer);
        return $this;
    }

    public function setConnectTimeOut(int $timeout)
    {
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        return $this;
    }

    public function setSslVerifyPeer(bool $verifyPeer)
    {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $verifyPeer);
        return $this;
    }

    public function setSslVerifyHost(bool $verifyHost)
    {
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $verifyHost);
        return $this;
    }

    public function setExecTimeOut(int $timeout)
    {
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    public function setHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setParams(string $key, string $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function setBody(string $body)
    {
        $this->data = $body;
        return $this;
    }

    public function setContentType(string $contentType)
    {
        $this->headers['Content-Type'] = $contentType;
        return $this;
    }

    public function setHeaderFunction(Closure $callback)
    {
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, $callback);
        return $this;
    }

    private function build()
    {
        if (count($this->headers) > 0) {

            $h = [];
            foreach ($this->headers as $key => $value) {

                $h[] = $key . ': ' . $value;
            }

            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $h);

        }

        if (count($this->params) > 0) {

            $p = [];
            foreach ($this->params as $key => $value) {

                $p[] = $key . '=' . urlencode($value);
            }
            $param = implode('&', $p);
            $this->url .= '?' . $param;
        }
    }

    public function get(string $url)
    {
        $this->url = $url;

        $this->build();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);
        return curl_exec($this->curl);
    }

    public function post(string $url)
    {
        $this->url = $url;

        $this->build();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "POST");
        return curl_exec($this->curl);

    }

    public function delete(string $url)
    {
        $this->url = $url;

        $this->build();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        return curl_exec($this->curl);

    }

    public function put(string $url)
    {
        $this->url = $url;

        $this->build();

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        return curl_exec($this->curl);
    }

    public function close(): void
    {
        curl_close($this->curl);
    }

    public function reset() {

        curl_reset($this->curl);
        return $this;
    }

}
