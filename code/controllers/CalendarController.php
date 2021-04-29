<?php

/**
 * An extension of the controller class for calendar requests.
 */
class CalendarController extends Controller
{
    private static $allowed_actions = array(
        'feed'
    );
    
    /**
     * Answers the event data within the calendar as a JSON feed.
     *
     * @param SS_HTTPRequest $request
     * @return SS_HTTPResponse
     */
    public function feed(SS_HTTPRequest $request)
    {
        if ($request->isAjax()) {
            
            $data = array();
            
            $response = $this->getResponse();
            
            $response->addHeader('Content-Type', 'text/json');
            
            $response->setBody(Convert::raw2json($data));
            
            return $response;
            
        }
    }
}
