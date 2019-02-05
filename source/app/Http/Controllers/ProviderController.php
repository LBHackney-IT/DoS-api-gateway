<?php

namespace App\Http\Controllers;

use App\Component\Model\Provider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProviderController extends AbstractEventController
{

    public function create(Request $request)
    {
        $jobName = 'job.data.store';
        $jobData = [
            'type' => 'provider',
            'values' => $request->all(),
        ];
        $pushRawCorrelationId = $this->queue->push($jobName, $jobData, 'store');
        echo $pushRawCorrelationId;
        return response()->json(array_merge($jobData, ['pushRawCorrelationId' => $pushRawCorrelationId]));
    }

    public function update(Request $request, $id)
    {
        $jobName = 'job.data.update';
        $jobData = [
            'type' => 'provider',
            'id' => $id,
            'values' => $request->all(),
        ];
        $this->queue->setCorrelationId($id);
        $this->queue->push($jobName, $jobData, 'store');
        sleep(1);
        return $this->get($request, $id);
//        return response()->json(['jobName' => $jobName, 'jobData' => $jobData, 'queue' => 'store']);
    }

    public function get(Request $request, $id)
    {
        try {
            $data = $this->getCachedData('provider', $id);
            if (empty($data)) {
                $this->getEvent($request, $id);
                sleep(1);
                redirect()->route('provider', ['id' => $id]);
            }
            $model = new Provider($data);
//            Log::debug(print_r($model->responseData(), true), [__METHOD__]);
            return response()->json($model);
        }
        catch (Exception $e) {
            $data = [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            return response()->json($data, $e->getCode());
        }
    }

    public function getEvent(Request $request, $id)
    {
        $jobName = 'job.data.get';
        $jobData = [
            'queryType' => 'select',
            'type' => 'provider',
            'id' => $id,
            'values' => $request->all(),
        ];
        $this->queue->setCorrelationId($id);
        $this->queue->push($jobName, $jobData, 'store');
    }

    public function getIndex()
    {
        $jobName = 'job.data.get';
        $jobData = [
            'queryType' => 'index',
            'type' => 'provider',
        ];
        $this->queue->push($jobName, $jobData, 'store');
    }

    public function delete(Request $request, $id)
    {
        $jobName = 'job.data.delete';
        $jobData = [
            'queryType' => 'delete',
            'type' => 'provider',
            'id' => $id,
        ];
        $this->queue->setCorrelationId($id);
        $this->queue->push($jobName, $jobData, 'store');
        return response()->json(['jobName' => $jobName, 'jobData' => $jobData, 'queue' => 'store']);
    }
}
