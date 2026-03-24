<?php

namespace App\Http\Controllers;

use App\Services\NodeInventoryService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InventoryPartController extends Controller
{
    public function __construct(private readonly NodeInventoryService $inventory)
    {
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $page = max(1, (int) $request->query('page', 1));

        $defaultPagination = [
            'page' => 1,
            'per_page' => 9,
            'total' => 0,
            'total_pages' => 1,
            'has_prev' => false,
            'has_next' => false,
        ];

        try {
            $result = $this->inventory->listParts([
                'q' => $search,
                'page' => $page,
                'per_page' => 9,
            ]);

            return view('inventory-parts.index', [
                'parts' => $result['items'] ?? [],
                'pagination' => $result['meta'] ?? $defaultPagination,
                'search' => $search,
                'errorMessage' => null,
            ]);
        } catch (RequestException $exception) {
            return view('inventory-parts.index', [
                'parts' => [],
                'pagination' => $defaultPagination,
                'search' => $search,
                'errorMessage' => 'Nao foi possivel acessar a API de estoque no Node.',
            ]);
        }
    }

    public function create()
    {
        return view('inventory-parts.form', [
            'mode' => 'create',
            'part' => null,
        ]);
    }

    public function store(Request $request)
    {
        $payload = $this->validatePayload($request);

        try {
            $this->inventory->createPart($payload);

            return redirect()
                ->route('inventory-parts.index')
                ->with('status', 'Peca criada com sucesso.');
        } catch (RequestException $exception) {
            return back()
                ->withInput()
                ->withErrors($this->extractNodeErrors($exception, 'Falha ao criar peca na API Node.'));
        }
    }

    public function edit(int $id)
    {
        try {
            $part = $this->inventory->findPart($id);

            if (!$part) {
                abort(404);
            }

            return view('inventory-parts.form', [
                'mode' => 'edit',
                'part' => $part,
            ]);
        } catch (RequestException $exception) {
            return redirect()
                ->route('inventory-parts.index')
                ->withErrors(['api' => 'Nao foi possivel consultar a API Node.']);
        }
    }

    public function update(Request $request, int $id)
    {
        $payload = $this->validatePayload($request);

        try {
            $this->inventory->updatePart($id, $payload);

            return redirect()
                ->route('inventory-parts.index')
                ->with('status', 'Peca atualizada com sucesso.');
        } catch (RequestException $exception) {
            return back()
                ->withInput()
                ->withErrors($this->extractNodeErrors($exception, 'Falha ao atualizar peca na API Node.'));
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->inventory->deletePart($id);

            return redirect()
                ->route('inventory-parts.index')
                ->with('status', 'Peca removida com sucesso.');
        } catch (RequestException $exception) {
            return redirect()
                ->route('inventory-parts.index')
                ->withErrors($this->extractNodeErrors($exception, 'Falha ao remover peca na API Node.'));
        }
    }

    /**
     * @throws ValidationException
     */
    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'nome_peca' => ['required', 'string', 'max:255'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'quantidade_disponivel' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function extractNodeErrors(RequestException $exception, string $fallbackMessage): array
    {
        $response = $exception->response;

        if (!$response) {
            return ['api' => $fallbackMessage];
        }

        $errors = $response->json('errors');

        if (is_array($errors) && count($errors) > 0) {
            return ['api' => implode(' ', $errors)];
        }

        $message = $response->json('message');

        if (is_string($message) && $message !== '') {
            return ['api' => $message];
        }

        return ['api' => $fallbackMessage];
    }
}
