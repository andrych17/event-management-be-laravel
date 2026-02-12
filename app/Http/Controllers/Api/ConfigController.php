<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of configs with pagination and filtering
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $groupCode = $request->input('group_code');
        $isActive = $request->input('is_active');
        $sortBy = $request->input('sort_by', 'group_code');
        $sortDir = $request->input('sort_dir', 'asc');

        // Validate sort parameters
        $allowedSortFields = ['group_code', 'value', 'descr', 'is_active', 'created_at', 'parent_id'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'group_code';
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'asc';

        $query = Config::query();

        // Filter by group_code
        if ($groupCode) {
            $query->where('group_code', $groupCode);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('value', 'like', "%{$search}%")
                  ->orWhere('descr', 'like', "%{$search}%");
            });
        }

        // Active filter
        if ($isActive !== null) {
            $query->where('is_active', filter_var($isActive, FILTER_VALIDATE_BOOLEAN));
        }

        $query->with('parent');

        // Apply sorting
        $query->orderBy($sortBy, $sortDir);

        // Add secondary sort for consistency
        if ($sortBy !== 'group_code') {
            $query->orderBy('group_code', 'asc');
        }
        if ($sortBy !== 'value') {
            $query->orderBy('value', 'asc');
        }

        $configs = $query->paginate($perPage);

        return response()->json($configs);
    }

    /**
     * Get only active configs for dropdowns (optionally filtered by group_code)
     */
    public function active(Request $request)
    {
        $groupCode = $request->input('group_code');

        $query = Config::active()->orderBy('value', 'asc');

        if ($groupCode) {
            $query->where('group_code', $groupCode);
        }

        $configs = $query->get();

        return $this->success('Active configs retrieved successfully', $configs);
    }

    /**
     * Store a newly created config
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_code' => 'required|string|in:Location,Floor',
            'parent_id' => 'nullable|exists:configs,id',
            'value' => 'required|string|max:255',
            'descr' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        // Validate parent_id based on group_code
        if ($request->group_code === 'Floor' && !$request->parent_id) {
            return $this->validationError([
                'parent_id' => ['Floor must have a parent location.']
            ]);
        }

        if ($request->group_code === 'Location' && $request->parent_id) {
            return $this->validationError([
                'parent_id' => ['Location cannot have a parent.']
            ]);
        }

        $config = Config::create($validator->validated());
        $config->load('parent');

        return $this->success('Config created successfully', $config, 201);
    }

    /**
     * Display the specified config
     */
    public function show(Config $config)
    {
        $config->load(['creator', 'updater']);
        return $this->success('Config retrieved successfully', $config);
    }

    /**
     * Update the specified config
     */
    public function update(Request $request, Config $config)
    {
        $validator = Validator::make($request->all(), [
            'group_code' => 'sometimes|required|string|in:Location,Floor',
            'parent_id' => 'nullable|exists:configs,id',
            'value' => 'sometimes|required|string|max:255',
            'descr' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors()->toArray());
        }

        $groupCode = $request->input('group_code', $config->group_code);
        $parentId = $request->input('parent_id', $config->parent_id);

        // Validate parent_id based on group_code
        if ($groupCode === 'Floor' && !$parentId) {
            return $this->validationError([
                'parent_id' => ['Floor must have a parent location.']
            ]);
        }

        if ($groupCode === 'Location' && $parentId) {
            return $this->validationError([
                'parent_id' => ['Location cannot have a parent.']
            ]);
        }

        $config->update($validator->validated());
        $config->load(['creator', 'updater', 'parent']);

        return $this->success('Config updated successfully', $config);
    }

    /**
     * Remove the specified config (soft delete)
     */
    public function destroy(Config $config)
    {
        // Check if any events use this config as location or floor
        $locationUsage = $config->locationEvents()->count();
        $floorUsage = $config->floorEvents()->count();

        if ($locationUsage > 0 || $floorUsage > 0) {
            return $this->error('Cannot delete config that is in use by events', [
                'config' => ['Cannot delete config that is in use by events']
            ], 422);
        }

        // Check if this location has any children (floors)
        $childrenCount = $config->children()->count();
        if ($childrenCount > 0) {
            return $this->error('Cannot delete location that has floors', [
                'config' => ['Cannot delete location that has floors. Please delete the floors first.']
            ], 422);
        }

        $config->delete();

        return $this->success('Config deleted successfully');
    }
}
