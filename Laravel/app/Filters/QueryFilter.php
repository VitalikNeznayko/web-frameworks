<?php

namespace App\Filters;

use Closure;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    private const RANGE_SUFFIXES = ['range' => ['_min', '_max'], 'date' => ['_from', '_to']];

    private array $values = [];

    private array $invalid = [];

    public function __construct(array $input)
    {
        foreach ($this->fields() as $name => $field) {
            foreach ($this->inputKeys($name, $field['type']) as $key) {
                if (! array_key_exists($key, $input)) {
                    continue;
                }

                $raw = is_string($input[$key]) ? trim($input[$key]) : $input[$key];

                if ($raw === null || $raw === '') {
                    continue;
                }

                $value = is_string($raw) ? $this->sanitize($field, $raw) : null;

                if ($value === null) {
                    $this->invalid[] = $key;

                    continue;
                }

                $this->values[$key] = $value;
            }
        }
    }

    abstract protected function fields(): array;

    public function apply(Builder $query): Builder
    {
        foreach ($this->fields() as $name => $field) {
            $column = $query->qualifyColumn($field['column'] ?? $name);

            switch ($field['type']) {
                case 'text':
                    if ($this->has($name)) {
                        $query->where($column, 'like', '%'.$this->escapeLike($this->values[$name]).'%');
                    }
                    break;
                case 'range':
                case 'date':
                    [$from, $to] = $this->inputKeys($name, $field['type']);
                    $method = $field['type'] === 'date' ? 'whereDate' : 'where';

                    if ($this->has($from)) {
                        $query->{$method}($column, '>=', $this->values[$from]);
                    }

                    if ($this->has($to)) {
                        $query->{$method}($column, '<=', $this->values[$to]);
                    }
                    break;
                case 'relation':
                    if ($this->has($name)) {
                        $query->whereHas($field['relation'], fn (Builder $related) => $related->whereKey($this->values[$name]));
                    }
                    break;
                default:
                    if ($this->has($name)) {
                        $query->where($column, $this->values[$name]);
                    }
            }
        }

        return $query;
    }

    public function definitions(): array
    {
        return array_map(function (array $field) {
            if (isset($field['options']) && $field['options'] instanceof Closure) {
                $field['options'] = ($field['options'])();
            }

            return $field;
        }, $this->fields());
    }

    public function inputKeys(string $name, string $type): array
    {
        if (! isset(self::RANGE_SUFFIXES[$type])) {
            return [$name];
        }

        return array_map(fn (string $suffix) => $name.$suffix, self::RANGE_SUFFIXES[$type]);
    }

    public function value(string $key): string
    {
        return (string) ($this->values[$key] ?? '');
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function isActive(): bool
    {
        return $this->values !== [];
    }

    public function invalid(): array
    {
        return $this->invalid;
    }

    private function sanitize(array $field, string $raw): mixed
    {
        return match ($field['type']) {
            'exact', 'select', 'relation' => ctype_digit($raw) && strlen($raw) <= 18 && (int) $raw > 0 ? (int) $raw : null,
            'text' => mb_substr($raw, 0, 255),
            'enum' => array_key_exists($raw, $field['options']) ? $raw : null,
            'boolean' => in_array($raw, ['0', '1'], true) ? (int) $raw : null,
            'range' => is_numeric($raw) && abs((float) $raw) < 1e12 ? (float) $raw : null,
            'date' => $this->sanitizeDate($raw),
            default => null,
        };
    }

    private function sanitizeDate(string $raw): ?string
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $raw);

        return $date !== false && $date->format('Y-m-d') === $raw ? $raw : null;
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
