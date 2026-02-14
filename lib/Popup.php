<?php

class Popup
{
  private string $id;
  private string $buttonHtml;
  private string $contentHtml;
  private string $dialogClass;

  public function __construct(
    string $buttonHtml,
    string $contentHtml,
    string $dialogClass = 'absolute left-1/2 top-1/2 -translate-x-1/2 container -translate-y-1/2 rounded-xl p-4 border-2 bg-slate-800 text-white'
  ) {
    $this->id = str_replace('.', '_', uniqid('p_', true));
    $this->buttonHtml = $buttonHtml;
    $this->contentHtml = $contentHtml;
    $this->dialogClass = $dialogClass;
  }

  public function render(): string
  {
    $id = htmlspecialchars($this->id);

    return <<<HTML
    <span id="trigger_{$id}">
      {$this->buttonHtml}
    </span>
    <dialog id="dialog_{$id}" class="{$this->dialogClass}">
        <div class="dialog-content flex flex-col gap-4">
            {$this->contentHtml}
            <button 
                type="button" 
                class="absolute top-2.5 right-4 text-xl text-slate-400 uppercase tracking-widest hover:text-red-500"
                onclick="document.getElementById('dialog_{$id}').close()">
                ⨉
            </button>
        </div>
    </dialog>
    <script>
      (() => {
        const dialog = document.getElementById('dialog_{$id}');
        const trigger = document.querySelector('#trigger_{$id} button');
        trigger.addEventListener('click', () => dialog.showModal());
        dialog.addEventListener('click', (e) => {
          const dialogDimensions = dialog.getBoundingClientRect();
          if (
            e.clientX < dialogDimensions.left ||
            e.clientX > dialogDimensions.right ||
            e.clientY < dialogDimensions.top ||
            e.clientY > dialogDimensions.bottom
          ) dialog.close();
        });
      })();
    </script>
HTML;
  }
}
