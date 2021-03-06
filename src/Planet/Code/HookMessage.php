<?php

/**
 * Class MsgStore
 */
class HookMessage
{
    /**
     * @var string
     */
    const COMMIT_ERROR = <<<TEXT
   _____ ____  __  __ __  __ _____ _______   ______ _____  _____   ____  _____    _
  / ____/ __ \|  \/  |  \/  |_   _|__   __| |  ____|  __ \|  __ \ / __ \|  __ \  | |
 | |   | |  | | \  / | \  / | | |    | |    | |__  | |__) | |__) | |  | | |__) | | |
 | |   | |  | | |\/| | |\/| | | |    | |    |  __| |  _  /|  _  /| |  | |  _  /  | |
 | |___| |__| | |  | | |  | |_| |_   | |    | |____| | \ \| | \ \| |__| | | \ \  |_|
  \_____\____/|_|  |_|_|  |_|_____|  |_|    |______|_|  \_\_|  \_\\\\____/|_|  \_\ (_)


´´´´´´´´´´´´´´´´´´´ ¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶´´´´´´´´´´´´´´´´´´´`
´´´´´´´´´´´´´´´´´¶¶¶¶¶¶´´´´´´´´´´´´´¶¶¶¶¶¶¶´´´´´´´´´´´´´´´´
´´´´´´´´´´´´´´¶¶¶¶´´´´´´´´´´´´´´´´´´´´´´´¶¶¶¶´´´´´´´´´´´´´´
´´´´´´´´´´´´´¶¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´´´´´´´´´´´
´´´´´´´´´´´´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´´´´´´´´´´
´´´´´´´´´´´¶¶´´´´´´´´´´´´´´´´´´´´´`´´´´´´´´´´´¶¶´´´´´´´´´´`
´´´´´´´´´´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´´´´´´´´´
´´´´´´´´´´¶¶´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´¶¶´´´´´´´´´´
´´´´´´´´´´¶¶´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´¶´´´´´´´´´´
´´´´´´´´´´¶¶´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´¶´´´´´´´´´´
´´´´´´´´´´¶¶´´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´¶¶´´´´´´´´´´
´´´´´´´´´´¶¶´´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´¶¶´´´´´´´´´´
´´´´´´´´´´´¶¶´¶¶´´´¶¶¶¶¶¶¶¶´´´´´¶¶¶¶¶¶¶¶´´´¶¶´¶¶´´´´´´´´´´´
´´´´´´´´´´´´¶¶¶¶´¶¶¶¶¶¶¶¶¶¶´´´´´¶¶¶¶¶¶¶¶¶¶´¶¶¶¶¶´´´´´´´´´´´
´´´´´´´´´´´´´¶¶¶´¶¶¶¶¶¶¶¶¶¶´´´´´¶¶¶¶¶¶¶¶¶¶´¶¶¶´´´´´´´´´´´´´
´´´´¶¶¶´´´´´´´¶¶´´¶¶¶¶¶¶¶¶´´´´´´´¶¶¶¶¶¶¶¶¶´´¶¶´´´´´´¶¶¶¶´´´
´´´¶¶¶¶¶´´´´´¶¶´´´¶¶¶¶¶¶¶´´´¶¶¶´´´¶¶¶¶¶¶¶´´´¶¶´´´´´¶¶¶¶¶¶´´
´´¶¶´´´¶¶´´´´¶¶´´´´´¶¶¶´´´´¶¶¶¶¶´´´´¶¶¶´´´´´¶¶´´´´¶¶´´´¶¶´´
´¶¶¶´´´´¶¶¶¶´´¶¶´´´´´´´´´´¶¶¶¶¶¶¶´´´´´´´´´´¶¶´´¶¶¶¶´´´´¶¶¶´
¶¶´´´´´´´´´¶¶¶¶¶¶¶¶´´´´´´´¶¶¶¶¶¶¶´´´´´´´¶¶¶¶¶¶¶¶¶´´´´´´´´¶¶
¶¶¶¶¶¶¶¶¶´´´´´¶¶¶¶¶¶¶¶´´´´¶¶¶¶¶¶¶´´´´¶¶¶¶¶¶¶¶´´´´´´¶¶¶¶¶¶¶¶
´´¶¶¶¶´¶¶¶¶¶´´´´´´¶¶¶¶¶´´´´´´´´´´´´´´¶¶¶´¶¶´´´´´¶¶¶¶¶¶´¶¶¶´
´´´´´´´´´´¶¶¶¶¶¶´´¶¶¶´´¶¶´´´´´´´´´´´¶¶´´¶¶¶´´¶¶¶¶¶¶´´´´´´´´
´´´´´´´´´´´´´´¶¶¶¶¶¶´¶¶´¶¶¶¶¶¶¶¶¶¶¶´¶¶´¶¶¶¶¶¶´´´´´´´´´´´´´´
´´´´´´´´´´´´´´´´´´¶¶´¶¶´¶´¶´¶´¶´¶´¶´¶´¶´¶¶´´´´´´´´´´´´´´´´´
´´´´´´´´´´´´´´´´¶¶¶¶´´¶´¶´¶´¶´¶´¶´¶´¶´´´¶¶¶¶¶´´´´´´´´´´´´´´
´´´´´´´´´´´´¶¶¶¶¶´¶¶´´´¶¶¶¶¶¶¶¶¶¶¶¶¶´´´¶¶´¶¶¶¶¶´´´´´´´´´´´´
´´´´¶¶¶¶¶¶¶¶¶¶´´´´´¶¶´´´´´´´´´´´´´´´´´¶¶´´´´´´¶¶¶¶¶¶¶¶¶´´´´
´´´¶¶´´´´´´´´´´´¶¶¶¶¶¶¶´´´´´´´´´´´´´¶¶¶¶¶¶¶¶´´´´´´´´´´¶¶´´´
´´´´¶¶¶´´´´´¶¶¶¶¶´´´´´¶¶¶¶¶¶¶¶¶¶¶¶¶¶¶´´´´´¶¶¶¶¶´´´´´¶¶¶´´´´
´´´´´´¶¶´´´¶¶¶´´´´´´´´´´´¶¶¶¶¶¶¶¶¶´´´´´´´´´´´¶¶¶´´´¶¶´´´´´´
´´´´´´¶¶´´¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´¶¶´´´´´´
´´´´´´´¶¶¶¶´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´´¶¶¶¶´´´´´´´

TEXT;
    
    /**
     * @var string
     */
    const COMMIT_OK = <<<TEXT

´´´´´´´´´´´´´´´´´´´´´´¶¶¶¶¶¶¶¶¶´´´´´´´´´´´´´
´´´´´´´´´´´´´´´´´´´´¶¶´´´´´´´´´´¶¶´´´´´´´´´´
´´´´´´¶¶¶¶¶´´´´´´´¶¶´´´´´´´´´´´´´´¶¶´´´´´´´´
´´´´´¶´´´´´¶´´´´¶¶´´´´´¶¶´´´´¶¶´´´´´¶¶´´´´´´
´´´´´¶´´´´´¶´´´¶¶´´´´´´¶¶´´´´¶¶´´´´´´´¶¶´´´´
´´´´´¶´´´´¶´´¶¶´´´´´´´´¶¶´´´´¶¶´´´´´´´´¶¶´´´
´´´´´´¶´´´¶´´´¶´´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´
´´´´¶¶¶¶¶¶¶¶¶¶¶¶´´´´´´´´´´´´´´´´´´´´´´´´¶¶´´
´´´¶´´´´´´´´´´´´¶´¶¶´´´´´´´´´´´´´¶¶´´´´´¶¶´´
´´¶¶´´´´´´´´´´´´¶´´¶¶´´´´´´´´´´´´¶¶´´´´´¶¶´´
´¶¶´´´¶¶¶¶¶¶¶¶¶¶¶´´´´¶¶´´´´´´´´¶¶´´´´´´´¶¶´´
´¶´´´´´´´´´´´´´´´¶´´´´´¶¶¶¶¶¶¶´´´´´´´´´¶¶´´´
´¶¶´´´´´´´´´´´´´´¶´´´´´´´´´´´´´´´´´´´´¶¶´´´´
´´¶´´´¶¶¶¶¶¶¶¶¶¶¶¶´´´´´´´´´´´´´´´´´´´¶¶´´´´´
´´¶¶´´´´´´´´´´´¶´´¶¶´´´´´´´´´´´´´´´´¶¶´´´´´´
´´´¶¶¶¶¶¶¶¶¶¶¶¶´´´´´¶¶´´´´´´´´´´´´¶¶´´´´´´´´
´´´´´´´´´´´´´´´´´´´´´´´¶¶¶¶¶¶¶¶¶¶¶´´´´´´´´´´

TEXT;
    
    /**
     * @var string
     */
    const HEADER = <<<TEXT
   _____  _____     __      __     _____  _____
  / ____|/ ____|   /\ \    / /\   |  __ \|  __ \   /\
 | (___ | |       /  \ \  / /  \  | |__) | |  | | /  \
  \___ \| |      / /\ \ \/ / /\ \ |  _  /| |  | |/ /\ \
  ____) | |____ / ____ \  / ____ \| | \ \| |__| / ____ \
 |_____/ \_____/_/    \_\/_/    \_\_|  \_\_____/_/    \_\
 
  _____  _               _   _ ______ _______    _____ ____  _____  ______    _____ _    _ ______ _____ _  __
 |  __ \| |        /\   | \ | |  ____|__   __|  / ____/ __ \|  __ \|  ____|  / ____| |  | |  ____/ ____| |/ /
 | |__) | |       /  \  |  \| | |__     | |    | |   | |  | | |  | | |__    | |    | |__| | |__ | |    | ' /
 |  ___/| |      / /\ \ | . ` |  __|    | |    | |   | |  | | |  | |  __|   | |    |  __  |  __|| |    |  <
 | |    | |____ / ____ \| |\  | |____   | |    | |___| |__| | |__| | |____  | |____| |  | | |___| |____| . \
 |_|    |______/_/    \_\_| \_|______|  |_|     \_____\____/|_____/|______|  \_____|_|  |_|______\_____|_|\_\
 
TEXT;
}
