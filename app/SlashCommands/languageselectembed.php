<?php

namespace App\SlashCommands;

use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;
use Laracord\Commands\SlashCommand;
use function phar\build;


class languageselectembed extends SlashCommand
{

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'languageselectembed';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'The Languageselectembed slash command.';

    /**
     * The command options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The permissions required to use the command.
     *
     * @var array
     */
    protected $permissions = [];

    /**
     * Indicates whether the command requires admin permissions.
     *
     * @var bool
     */
    protected $admin = false;

    /**
     * Indicates whether the command should be displayed in the commands list.
     *
     * @var bool
     */
    protected $hidden = false;

    protected $guild = '1349034227836129281';

    /**
     * Handle the slash command.
     *
     * @param \Discord\Parts\Interactions\Interaction $interaction
     * @return mixed
     * @throws \Exception
     */
    public function handle($interaction)
    {
        $interaction->respondWithMessage(
            $this
                ->message()
                ->title('Welcome to GoBerly Community Discord!')
                ->content('We are thrilled to have you here and excited that you have decided to join our community. You have arrived at the entrance area of our Discord, where you can explore all that our server has to offer. Currently, you are considered unverified and have limited access to our channels and content.

To unlock more features, simply select your preferred language using the interactive buttons located below this message. Please note that you can only choose one language and any previously selected languages will be removed.

Once you have selected your language, you will gain full access to our Discord server and can start chatting and connecting with other members.

We hope you have a great time here and look forward to seeing you in our chats!')
                ->button('German', route: 'language_select:german')
                ->button('English', route: 'language_select:english')
                ->button('Remove Language', style: 'danger', route: 'language_select:remove')
                ->build()
        );
    }

    /**
     * The command interaction routes.
     */
    public function interactions(): array
    {
        return [
            'language_select:german' => fn(Interaction $interaction) => $this->handleGerman($interaction),
            'language_select:english' => fn(Interaction $interaction) => $this->handleEnglish($interaction),
            'language_select:remove' => fn(Interaction $interaction) => $this->handleRemove($interaction),
        ];
    }

    protected function removeRole(Interaction $interaction, string $role): void
    {
        $member = $interaction->member;

        if ($member->roles->has($role)) {
            $member->removeRole($role);
        } else {
            return;
        }
    }

    protected function addrole(Interaction $interaction, string $role): void {
        $member = $interaction->member;

        if (!$member->roles->has($role)) {
            $member->addRole($role);
        } else {
            return;
        }
    }

    protected function handleGerman(Interaction $interaction): void
    {
        $english = $interaction->guild->roles->find('EN');;
        $german = $interaction->guild->roles->find('DE');;
        $member = $interaction->member;
        $roles = $member->roles;



        if ($roles->has($english)) {
            $this->removeRole($interaction, $english);
            $this->addrole($interaction, $german);
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('German')
                    ->content('🎉 Du bist erfolgreich der deutschen Sprachrolle zugewiesen worden!')
                    ->build()
            );
        } else if (!$roles->has($german)){
            $this->addrole($interaction, $german);
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('German')
                    ->content('🎉 Du bist erfolgreich der deutschen Sprachrolle zugewiesen worden!')
                    ->build()
            );
        } else if ($roles->has($german)) {
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('German')
                    ->content('Du hast bereits die Rolle')
                    ->build()
            );
        }
    }

    private function handleEnglish(Interaction $interaction): void
    {
        $english = $interaction->guild->roles->find('EN');;
        $german = $interaction->guild->roles->find('DE');;
        $member = $interaction->member;
        $roles = $member->roles;

        if ($roles->has($german)) {
            $this->removeRole($interaction, $german);
            $this->removeRole($interaction, $english);
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('English')
                    ->content('🎉 You\'ve been assigned the English role! Welcome to the GoBerly Discord!')
                    ->build()
            );
        } else if (!$roles->has($english)){
            $this->addrole($interaction, $english);
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('English')
                    ->content('🎉 You\'ve been assigned the English role! Welcome to the GoBerly Discord!')
                    ->build()
            );
        } else if ($roles->has($english)){
            $interaction->respondWithMessage(
                $this
                    ->message()
                    ->title('English')
                    ->content('You already have the role')
                    ->build()
            );
        }
    }

    private function handleRemove(Interaction $interaction): void
    {
        $member = $interaction->member;

        $roles = $member->roles;
        $reason = 'User request';

        $english = $interaction->guild->roles->find('EN');;
        $german = $interaction->guild->roles->find('DE');;

        if ($roles->has($english)) {
            $member->removeRole($english, $reason);
        } else if ($roles->has($german)) {
            $member->removeRole($german, $reason);
        } else {
            $interaction->respondWithMessage(
                $this
                ->message()
                ->content('You dont have an language role yet. Please report it if this is an bug')
                ->build(),
                true
            );
        }
    }
}
