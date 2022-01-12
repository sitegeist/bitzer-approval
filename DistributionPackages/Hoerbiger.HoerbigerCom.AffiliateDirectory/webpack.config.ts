import composerJson from './composer.json';
import {buildComponentsConfig} from '@hoerbiger/shared/webpack.config';

export default buildComponentsConfig(composerJson, './DummyEntry.ts');
