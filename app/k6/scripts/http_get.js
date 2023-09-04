import http from 'k6/http';
import { htmlReport } from "https://raw.githubusercontent.com/benc-uk/k6-reporter/main/dist/bundle.js";
import { textSummary } from "https://jslib.k6.io/k6-summary/0.0.1/index.js";
import { check } from 'k6';

export const options = {
  stages: [
    { target:  5, duration: '30s' }
  ],
  thresholds: {
    http_req_failed: ['rate<0.01'], // http errors should be less than 1%
    http_req_duration: ['p(95)<200'], // 95% of requests should be below 200ms
  },
};

export default function () {
  const res = http.get('http://host.docker.internal:8080/');
  check(res, {
    'is status 200'        : (r) => r.status === 200,
    'verify homepage text' : (r) => r.body.includes('Hello world!'),
    'body size is 12'      : (r) => r.body.length == 12,
  });
};

export function handleSummary(data) {
  let returnData = {
     stdout: textSummary(data, { indent: " ", enableColors: true })
  };

  let outputDatetime = formatDate(new Date(), 'yyyyMMdd_HHmmss');
  let outputHtml = outputDatetime + "_summary.html";
  returnData[outputHtml]=htmlReport(data);

  return returnData;
}

function formatDate (date, format) {
  format = format.replace(/yyyy/g, date.getFullYear());
  format = format.replace(/MM/g, ('0' + (date.getMonth() + 1)).slice(-2));
  format = format.replace(/dd/g, ('0' + date.getDate()).slice(-2));
  format = format.replace(/HH/g, ('0' + date.getHours()).slice(-2));
  format = format.replace(/mm/g, ('0' + date.getMinutes()).slice(-2));
  format = format.replace(/ss/g, ('0' + date.getSeconds()).slice(-2));
  format = format.replace(/SSS/g, ('00' + date.getMilliseconds()).slice(-3));
  return format;
};