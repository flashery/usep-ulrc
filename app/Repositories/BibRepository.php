<?php

namespace App\Repositories;

use App\Bib;
use App\Department;

class BibRepository
{
    public function getBibs($data = null)
    {
        if (isset($data['department_id'])) {
            $department = Department::whereId($data['department_id'])->with('subjects')->first();
            $subject_ids = [];
            foreach ($department->subjects as $subject) {
                array_push($subject_ids, $subject->id);
            }
            return Bib::with('marc_tags', 'subjects', 'volumes')->whereHas('subjects', function ($q) use ($subject_ids) {
                $q->whereIn('subject_id', $subject_ids);
            })->get();
        }

        return Bib::with('marc_tags', 'subjects', 'volumes')->get();
    }

    public function getSpecificMarcTag(array $marc_tags, $marc_tag)
    {
        foreach ($marc_tags as $marc) {
            if ($marc['marc_tag'] === $marc_tag) return $marc['pivot']['value'];
        }
    }

    public function extractDateOfPub($call_number)
    {
        return substr($call_number, -4, strlen($call_number));
    }

    public function getDeweyDecimal($call_number)
    {
        return str_pad((int) substr($call_number, 5, 3), 3, '0', STR_PAD_LEFT);
    }
}
